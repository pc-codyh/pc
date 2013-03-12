package com.pongchamp.pc;

import android.content.Context;
import android.graphics.Typeface;
import android.widget.TextView;

public class CalibriTextView extends TextView
{
	public CalibriTextView(Context context)
	{
		super(context);
		
		setFont(context);
	}
	
	private void setFont(Context context)
	{
		Typeface font = Typeface.createFromAsset(context.getAssets(), "CALIBRI.TTF");
		
		setTypeface(font);
	} 
}
