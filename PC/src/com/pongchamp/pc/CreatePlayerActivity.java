package com.pongchamp.pc;

import java.net.URL;
import java.util.ArrayList;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Intent;
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
public class CreatePlayerActivity extends Activity
{
	PCUser _activeUser = null;
	Button _submitButton = null;
	EditText _newPlayerName = null;
	ImageView _createPlayerResultImage = null;
	TextView _createPlayerResultText = null;
	TextView _namePrompt = null;
	
	boolean _didCreatePlayer = false;
	String _newPlayer = null;
	
	String _createPlayerURL = "http://www.pongchamp.com/createplayer.php";
	
	public void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_createplayer);
        
        StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
        StrictMode.setThreadPolicy(policy);
        
        _submitButton = (Button) findViewById(R.id.createplayer_submit);
        _newPlayerName = (EditText) findViewById(R.id.createplayer_name);
        _createPlayerResultImage = (ImageView) findViewById(R.id.createplayer_result_image);
        _createPlayerResultText = (TextView) findViewById(R.id.createplayer_result_text);
        _namePrompt = (TextView) findViewById(R.id.createplayer_name_prompt);
        
        _createPlayerResultImage.setVisibility(View.INVISIBLE);
        _createPlayerResultText.setVisibility(View.INVISIBLE);
        
        Bundle extras = getIntent().getExtras();
        
        _activeUser = new PCUser(extras.getString("ActiveUsername"));
        
        onSubmitButtonPressed();
        
        new Utilities().setFont(getApplicationContext(),
        						_createPlayerResultText,
        						_namePrompt,
        						_newPlayerName,
        						_submitButton);
	}
	
	public void onSubmitButtonPressed()
	{
		if (_didCreatePlayer)
		{
			_submitButton.setBackgroundResource(R.drawable.button);
			_submitButton.setText("Back");
			
			_newPlayerName.setEnabled(false);
			
			_newPlayerName.setBackgroundResource(R.drawable.rounded_textfield_disabled);
		}
		
		_submitButton.setOnClickListener(new View.OnClickListener()
		{	
			public void onClick(View arg0)
			{
				if (!_didCreatePlayer)
				{
					if (_newPlayerName.getText().toString().equals(""))
					{
						_createPlayerResultImage.setImageResource(R.drawable.xmark);
						_createPlayerResultText.setText(R.string.nameless);
						
						_createPlayerResultImage.setVisibility(View.VISIBLE);
				        _createPlayerResultText.setVisibility(View.VISIBLE);
					}
					else if (_newPlayerName.getText().toString().equalsIgnoreCase("and"))
					{
						_createPlayerResultImage.setImageResource(R.drawable.xmark);
						_createPlayerResultText.setText(R.string.name_and);
						
						_createPlayerResultImage.setVisibility(View.VISIBLE);
				        _createPlayerResultText.setVisibility(View.VISIBLE);
					}
					else
					{
						new PerformBackgroundTaskCreatePlayer().execute();
					}
				}
				else
				{
					endActivity();
				}
			}
		});
	}
	
	private boolean checkResult(String result)
	{
		if (result.equalsIgnoreCase("1"))
		{
			return true;
		}
		
		return false;
	}
	
	private void endActivity()
	{
		Intent intent = new Intent(CreatePlayerActivity.this, MenuActivity.class);
		
		if (_newPlayer != null)
		{
			intent.putExtra("NewPlayerName", _newPlayer);
			
			setResult(CreatePlayerActivity.RESULT_OK, intent);
		}
		else
		{
			setResult(CreatePlayerActivity.RESULT_CANCELED, intent);
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
	
	private class PerformBackgroundTaskCreatePlayer extends AsyncTask<URL, Integer, String>
	{
		@Override
		protected String doInBackground(URL... params)
		{
			String result = null;
			
			try
			{
				HTTPHelper httpHelper = new HTTPHelper();
				ArrayList<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);
				
				nameValuePairs.add(new BasicNameValuePair("name", _newPlayerName.getText().toString()));
				nameValuePairs.add(new BasicNameValuePair("username", _activeUser.getUsername()));
								
				result = httpHelper.executeHttpPost(nameValuePairs, _createPlayerURL);
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
				if (checkResult(result))
				{
					_createPlayerResultImage.setImageResource(R.drawable.checkmark);
					_createPlayerResultText.setText(R.string.createplayer_success);
					
					_didCreatePlayer = true;
					_newPlayer = _newPlayerName.getText().toString();
					
					onSubmitButtonPressed();
				}
				else
				{
					_createPlayerResultImage.setImageResource(R.drawable.xmark);
					_createPlayerResultText.setText(R.string.createplayer_failure);
				}
				
				_createPlayerResultImage.setVisibility(View.VISIBLE);
		        _createPlayerResultText.setVisibility(View.VISIBLE);
			}
			else
			{
				new Utilities().showNoInternetConnectionAlert(getApplicationContext());
			}
		}
	}
}
