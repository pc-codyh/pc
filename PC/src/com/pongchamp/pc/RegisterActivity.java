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

public class RegisterActivity extends Activity
{
	Button _submitRegistration = null;
	EditText _usernameField = null;
	EditText _passwordField = null;
	EditText _confirmPasswordField = null;
	ImageView _registrationResultImage = null;
	TextView _registrationResultText = null;
	TextView _usernamePrompt = null;
	TextView _passwordPrompt = null;
	TextView _confirmPasswordPrompt = null;
	boolean _hasRegistered = false;
	
	String _registrationURL = "http://www.pongchamp.com/registrations.php";
	
	@SuppressLint("NewApi")
	@Override
    public void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);
        
        StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
        StrictMode.setThreadPolicy(policy);
        
        _submitRegistration = (Button) findViewById(R.id.registration_submit);
        _usernameField = (EditText) findViewById(R.id.registration_newUsername);
        _passwordField = (EditText) findViewById(R.id.registration_newPassword);
        _confirmPasswordField = (EditText) findViewById(R.id.registration_confirmPassword);
        _registrationResultImage = (ImageView) findViewById(R.id.registration_result_image);
        _registrationResultText = (TextView) findViewById(R.id.registration_result_text);
        _usernamePrompt = (TextView) findViewById(R.id.registration_username_prompt);
        _passwordPrompt = (TextView) findViewById(R.id.registration_password_prompt);
        _confirmPasswordPrompt = (TextView) findViewById(R.id.registration_confirmpassword_prompt);
        
        onSubmitRegistrationPressed();
        
        _registrationResultImage.setVisibility(View.INVISIBLE);
        _registrationResultText.setVisibility(View.INVISIBLE);
        
        new Utilities().setFont(getApplicationContext(),
        Typeface.BOLD,
	    _registrationResultText,
	    _usernamePrompt,
	    _passwordPrompt,
	    _confirmPasswordPrompt,
	    _usernameField,
	    _passwordField,
	    _confirmPasswordField,
	    _submitRegistration);
    }
	
	private void onSubmitRegistrationPressed()
	{
		if (_hasRegistered)
		{
			_submitRegistration.setBackgroundResource(R.drawable.button);
			_submitRegistration.setText("Back");
			
			_usernameField.setEnabled(false);
			_passwordField.setEnabled(false);
			_confirmPasswordField.setEnabled(false);
			
			_usernameField.setBackgroundResource(R.drawable.rounded_textfield_disabled);
			_passwordField.setBackgroundResource(R.drawable.rounded_textfield_disabled);
			_confirmPasswordField.setBackgroundResource(R.drawable.rounded_textfield_disabled);
		}
		
		_submitRegistration.setOnClickListener(new View.OnClickListener()
		{
			public void onClick(View v)
			{	
				if (!_hasRegistered)
				{
					String username = _usernameField.getText().toString();
					String password = _passwordField.getText().toString();
					String confirmPassword = _confirmPasswordField.getText().toString();

					if (checkPasswords(password, confirmPassword) &&
						checkPasswordsEqual(password, confirmPassword) &&
						checkUsername(username))
					{
						new PerformBackgroundTaskRegister().execute();
					}
					else
					{
						_registrationResultImage.setVisibility(View.VISIBLE);
						_registrationResultText.setVisibility(View.VISIBLE);
						_registrationResultImage.setImageResource(R.drawable.xmark);
						
						if (!checkUsername(username))
						{
							_registrationResultText.setText(R.string.registration_invalid_username);
						}
						else if (!checkPasswords(password, confirmPassword))
						{	
							_registrationResultText.setText(R.string.registration_invalid_password);
						}
						else if (!checkPasswordsEqual(password, confirmPassword))
						{
							_registrationResultText.setText(R.string.registration_passwords_do_not_match);
						}
					}
				}
				else
				{
					finish();
				}
			}
		});
	}
	
	private boolean checkPasswords(String password, String confirmPassword)
	{
		if ((password.length() == confirmPassword.length()) &&
			 password.length() >= 6)
		{
			return true;
		}
		
		return false;
	}
	
	private boolean checkUsername(String username)
	{
		if (username.length() >= 6)
		{
			return true;
		}
		
		return false;
	}
	
	private boolean checkPasswordsEqual(String password, String confirmPassword)
	{
		if (password.equals(confirmPassword))
		{
			return true;
		}
		
		return false;
	}
	
	private boolean checkRegistrationResult(String result)
	{
		if (result.equalsIgnoreCase("1"))
		{
			return true;
		}
		
		return false;
	}
	
	private class PerformBackgroundTaskRegister extends AsyncTask<URL, Integer, String>
	{
		@Override
		protected String doInBackground(URL... params)
		{
			HTTPHelper httpHelper = new HTTPHelper();
			ArrayList<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);

			nameValuePairs.add(new BasicNameValuePair("username", _usernameField.getText().toString()));
			nameValuePairs.add(new BasicNameValuePair("password", _passwordField.getText().toString()));

			String result = httpHelper.executeHttpPost(nameValuePairs, _registrationURL);
			
			return result;
		}
		
		protected void onPostExecute(String result)
		{
			if (result != null)
			{
				_registrationResultImage.setVisibility(View.VISIBLE);
				_registrationResultText.setVisibility(View.VISIBLE);

				if (checkRegistrationResult(result))
				{
					_registrationResultImage.setImageResource(R.drawable.checkmark);
					_registrationResultText.setText(R.string.registration_successful);

					_hasRegistered = true;
					
					onSubmitRegistrationPressed();
				}
				else
				{
					_registrationResultImage.setImageResource(R.drawable.xmark);
					_registrationResultText.setText(R.string.registration_username_already_exists);
				}
			}
			else
			{
				new Utilities().showNoInternetConnectionAlert(getApplicationContext());
			}
		}
	}
}
