package com.pongchamp.pc;

import java.util.ArrayList;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;

public class CustomJSONParser
{
	public ArrayList<NameValuePair> parseJSONString(String jsonString)
	{
		String key = "";
		String value = "";
		int index = 0;
		ArrayList<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>();
		
		while (true)
		{
			index = jsonString.indexOf("\"");
			
			if (index == -1)
			{
				break;
			}
			
			jsonString = jsonString.substring(index + 1);
			
			index = jsonString.indexOf("\"");
			
			key = jsonString.substring(0, index);
			
			jsonString = jsonString.substring(index + 1);
			
			index = jsonString.indexOf("\"");
			
			jsonString = jsonString.substring(index + 1);
			
			index = jsonString.indexOf("\"");
			
			value = jsonString.substring(0, index);
			
			jsonString = jsonString.substring(index + 1);
			
			nameValuePairs.add(new BasicNameValuePair(key, value));
		}
		
		return nameValuePairs;
	}
}
