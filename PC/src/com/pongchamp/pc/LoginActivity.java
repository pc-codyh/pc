package com.pongchamp.pc;

import java.io.IOException;
import java.net.URL;
import java.util.ArrayList;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.graphics.Typeface;
import android.graphics.drawable.ColorDrawable;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.StrictMode;
import android.view.KeyEvent;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

@SuppressLint("NewApi")
public class LoginActivity extends Activity
{
	EditText _username = null;
	EditText _password = null;
	ImageView _loginResultImage = null;
	TextView _loginResultText = null;
	TextView _usernamePrompt = null;
	TextView _passwordPrompt = null;
	Button _submitLogin = null;
	
	String _activeUsername = null;
	boolean _isLoggedIn = false;
	ArrayList<NameValuePair> _players = null;
	
	String _loginURL = "http://www.pongchamp.com/login.php";
	String _getPlayersURL = "http://www.pongchamp.com/getplayers.php";
		
	public void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        
        StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
        StrictMode.setThreadPolicy(policy);
        
        _username = (EditText) findViewById(R.id.login_Username);
        _password = (EditText) findViewById(R.id.login_Password);
        _loginResultImage = (ImageView) findViewById(R.id.login_result_image);
        _loginResultText = (TextView) findViewById(R.id.login_result_text);
        _usernamePrompt = (TextView) findViewById(R.id.login_username_prompt);
        _passwordPrompt = (TextView) findViewById(R.id.login_password_prompt);
        _submitLogin = (Button) findViewById(R.id.login_submit);
        
        onSubmitLoginPressed();
        loadPersistentUsername();
        
        _loginResultImage.setVisibility(View.INVISIBLE);
        _loginResultText.setVisibility(View.INVISIBLE);
        
        new Utilities().setFont(getApplicationContext(),
        					    _loginResultText,
        					    _usernamePrompt,
        					    _passwordPrompt,
        					    _username,
        					    _password);
	}
	
	private void loadPersistentUsername()
	{
		SharedPreferences sp = getSharedPreferences("FileName", MODE_PRIVATE);
		String username = null;
		
		username = sp.getString("username", null);
		
		_username.setText(username);
	}
	
	private void onSubmitLoginPressed()
	{
		if (_isLoggedIn)
		{
			_submitLogin.setBackgroundResource(R.drawable.back);
			_submitLogin.setText("");
			_submitLogin.setMinimumHeight(60);
			
			_username.setEnabled(false);
			_password.setEnabled(false);
			
			_username.setBackgroundResource(R.drawable.rounded_textfield_disabled);
			_password.setBackgroundResource(R.drawable.rounded_textfield_disabled);
		}
		
		_submitLogin.setEnabled(true);
		
		_submitLogin.setOnClickListener(new View.OnClickListener()
		{	
			public void onClick(View v)
			{
				if (!_isLoggedIn)
				{
					new PerformBackgroundTaskLogin().execute();
				}
				else
				{
					endActivity();
				}
			}
		});
	}
	
	private void persistUsername(String username)
	{
		SharedPreferences sp = getSharedPreferences("FileName", MODE_PRIVATE);
		SharedPreferences.Editor spEditor = sp.edit();
		
		spEditor.putString("username", username);
		
		spEditor.commit();
	}
	
	private boolean checkLoginResult(String result)
	{
		if (result.equalsIgnoreCase("1"))
		{
			return true;
		}
		
		return false;
	}
	
	private void fetchPlayersForUsername()
	{
		new PerformBackgroundTaskGetPlayers().execute();
	}
	
	private void endActivity()
	{
		Intent intent = new Intent(LoginActivity.this, MenuActivity.class);
		ArrayList<String> players = null;
		
		if (_activeUsername != null)
		{
			players = new ArrayList<String>(_players.size());
			
			intent.putExtra("ActiveUsername", _activeUsername);
			
			for (NameValuePair player : _players)
			{
				if (player.getName().equals("name"))
				{
					players.add(player.getValue().toString());
				}
			}

			intent.putStringArrayListExtra("Players", players);
			
			setResult(LoginActivity.RESULT_OK, intent);
		}
		else
		{
			setResult(LoginActivity.RESULT_CANCELED, intent);
		}
		
		finish();
	}
	
	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event)
	{
		if (keyCode == KeyEvent.KEYCODE_BACK)
		{
			endActivity();
		}
		
		return true;
	}
	
	private class PerformBackgroundTaskLogin extends AsyncTask<URL, Integer, String>
	{
		@Override
		protected String doInBackground(URL... params)
		{
			String result = null;
			
			try
			{
				HTTPHelper httpHelper = new HTTPHelper();
				ArrayList<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);

				nameValuePairs.add(new BasicNameValuePair("username", _username.getText().toString()));
				nameValuePairs.add(new BasicNameValuePair("password", _password.getText().toString()));

				result = httpHelper.executeHttpGet(nameValuePairs, _loginURL);
			}
			catch (Exception e)
			{
				e.printStackTrace();
			}

			return result;
		}
		
		protected void onPostExecute(String result)
	    {
			_submitLogin.setEnabled(false);
			
			if (result != null)
			{
				_loginResultImage.setVisibility(View.VISIBLE);
				_loginResultText.setVisibility(View.VISIBLE);
				
				if (checkLoginResult(result))
				{
					_loginResultImage.setImageResource(R.drawable.checkmark);
					_loginResultText.setText(R.string.login_success);
					
					persistUsername(_username.getText().toString());
					
					_isLoggedIn = true;
					_activeUsername = _username.getText().toString();
					
					fetchPlayersForUsername();
				}
				else
				{
					_loginResultImage.setImageResource(R.drawable.xmark);
					_loginResultText.setText(R.string.login_failure);
				}
			}
			else
			{
				new Utilities().showNoInternetConnectionAlert(getApplicationContext());
			}
	    }
	}
	
	private class PerformBackgroundTaskGetPlayers extends AsyncTask<URL, Integer, String>
	{
		@Override
		protected String doInBackground(URL... params)
		{
			String result = null;
			
			try
			{
				HTTPHelper httpHelper = new HTTPHelper();
				ArrayList<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);
				
				nameValuePairs.add(new BasicNameValuePair("username", _activeUsername));
				
				result = httpHelper.executeHttpGet(nameValuePairs, _getPlayersURL);
			}
			catch (Exception e)
			{
				e.printStackTrace();
			}
			
			return result;
		}
		
		protected void onPostExecute(String result)
	    {
			if (result != null)
			{
				CustomJSONParser JSONParser = new CustomJSONParser();
				
				_players = JSONParser.parseJSONString(result);
				
				onSubmitLoginPressed();
			}
			else
			{
				new Utilities().showNoInternetConnectionAlert(getApplicationContext());
			}
	    }
	}
}
