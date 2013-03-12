package com.pongchamp.pc;

import android.widget.Button;
import android.widget.TextView;

public class PongPlayerDisplay
{
	Button _button = null;
	TextView _textView = null;
	
	public PongPlayerDisplay(Button btn, TextView tv, String name)
	{
		_button = btn;
		_textView = tv;
		
		_textView.setText(name);
	}
	
	public Button getButton()
	{
		return _button;
	}
	
	public TextView getTextView()
	{
		return _textView;
	}
}
