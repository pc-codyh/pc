package com.pongchamp.pc;

import java.util.ArrayList;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;

public class CustomAlertDialog
{
	public CustomAlertDialog(ArrayList<String> buttonTitles, String title, String msg, Context context)
	{
		AlertDialog.Builder alertDialog = new AlertDialog.Builder(context);
		
		alertDialog.setTitle(title);
		alertDialog.setMessage(msg);
		
		for (String str : buttonTitles)
		{
			alertDialog.setNeutralButton(str, new DialogInterface.OnClickListener()
			{	
				public void onClick(DialogInterface dialog, int which)
				{
					
				}
			});
		}
	}
}
