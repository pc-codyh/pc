package com.pongchamp.pc;

import java.io.ByteArrayOutputStream;
import java.io.InputStream;

import android.app.Activity;
import android.content.Intent;
import android.content.res.AssetManager;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.view.KeyEvent;
import android.widget.TextView;

public class HelpActivity extends Activity
{
	TextView _prompt = null;
	TextView _mainText = null;
	
	@Override
	public void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_help);
		
		_prompt = (TextView) findViewById(R.id.help_prompt);
		_mainText = (TextView) findViewById(R.id.help_text);
		
		new Utilities().setFont(getApplicationContext(),
								_prompt,
								_mainText);
		
		loadMainText();
	}
	
	private void loadMainText()
	{
		AssetManager am = getApplicationContext().getAssets();
		
		try
		{
			InputStream is = am.open("help.txt");
			
			ByteArrayOutputStream os = new ByteArrayOutputStream();
			
			_mainText.setText(readFromInputStream(is, os));
		}
		catch (Exception e)
		{
			e.printStackTrace();
		}
	}
	
	private String readFromInputStream(InputStream is, ByteArrayOutputStream os)
	{
		int i = 0;
		
		try
		{
			i = is.read();
			
			while (i != -1)
			{
				os.write(i);
				
				i = is.read();
			}
			
			is.close();
		}
		catch (Exception e)
		{
			e.printStackTrace();
		}
		
		return os.toString();
	}
}
