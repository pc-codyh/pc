package com.pongchamp.pc;

import java.util.ArrayList;

import org.apache.http.NameValuePair;

import android.content.Context;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.TextView;

public class CustomListAdapter extends ArrayAdapter<String>
{
	private LayoutInflater _inflater;
	private ArrayList<String> _rows;
	private Context _context;
	
	public CustomListAdapter(Context context, int textViewResourceId, ArrayList<String> rows)
	{
		super(context, textViewResourceId);
		
		_inflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
		_rows = rows;
		_context = context;
	}
	
	@Override
	public View getView(int position, View convertView, ViewGroup parent)
	{
		View view = _inflater.inflate(R.layout.customlistitem, null);
		
		TextView text = (TextView) view.findViewById(R.id.customlistitem_text);
		ImageView image = (ImageView) view.findViewById(R.id.customlistitem_image);
		
		if (position % 2 == 0)
		{
//			view.setBackgroundColor(Color.parseColor("#FFE794"));
			view.setBackgroundResource(R.drawable.listselector_even);
		}
		else
		{
//			view.setBackgroundColor(Color.parseColor("#FFF2C4"));
			view.setBackgroundResource(R.drawable.listselector_odd);
		}
		
		String item = _rows.get(position);
		
		text.setText(item);
		
		if (_rows.size() == 3)
		{
			switch (position)
			{
				case 0:
				{
					image.setImageResource(R.drawable.register_icon);
				}
					break;
					
				case 1:
				{
					image.setImageResource(R.drawable.login_icon);
				}
					break;
					
				case 2:
				{
					image.setImageResource(R.drawable.help_icon);
				}
					break;
					
				default:
					break;
			}
		}
		else
		{
			switch (position)
			{
				case 0:
				{
					image.setImageResource(R.drawable.logout_icon);
				}
					break;
					
				case 1:
				{
					image.setImageResource(R.drawable.viewstats_icon);
				}
					break;
					
				case 2:
				{
					image.setImageResource(R.drawable.help_icon);
				}
					break;
					
				case 3:
				{
					image.setImageResource(R.drawable.achievements_icon);
				}
					break;
					
				case 4:
				{
					image.setImageResource(R.drawable.createplayer_icon);
				}
					break;
					
				case 5:
				{
					image.setImageResource(R.drawable.randomteams_icon);
				}
					break;
					
				case 6:
				{
					image.setImageResource(R.drawable.changerules_icon);
				}
					break;
					
				case 7:
				{
					image.setImageResource(R.drawable.play_icon);
				}
					break;
					
				default:
					break;
			}
		}
		
		new Utilities().setFont(_context, text);
		
		return view;
	}
}