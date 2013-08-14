package com.pongchamp.pc;

import android.content.Context;
import android.graphics.Typeface;
import android.widget.TextView;
import android.widget.Toast;

public class Utilities
{
	public Utilities()
	{
		
	}
	
	public void showNoInternetConnectionAlert(Context context)
	{
		Toast.makeText(context, context.getString(R.string.no_internet_connection), Toast.LENGTH_LONG).show();
	}
	
	public void setFont(Context context, TextView... views)
	{
		for (TextView view : views)
		{
			view.setTypeface(Typeface.createFromAsset(context.getAssets(), "CALIBRI.TTF"));
		}
	}
	
	public String replaceSpacesWithEntities(String str)
	{
		return str.replace(" ", "%20");
	}
}
