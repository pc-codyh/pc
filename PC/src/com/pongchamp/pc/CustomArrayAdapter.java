package com.pongchamp.pc;

import android.content.Context;
import android.graphics.Color;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;

public class CustomArrayAdapter extends ArrayAdapter<String>
{
	public CustomArrayAdapter(Context context, int textViewResourceId)
	{
		super(context, textViewResourceId);
	}
	
	@Override
	public View getView(int position, View convertView, ViewGroup parent)
	{
		View view = super.getView(position, convertView, parent);
		
		if (position % 2 == 0)
		{
			view.setBackgroundColor(Color.parseColor("#FFE794"));
		}
		else
		{
			view.setBackgroundColor(Color.parseColor("#FFF2C4"));
		}
		
		return view;
	}
}
