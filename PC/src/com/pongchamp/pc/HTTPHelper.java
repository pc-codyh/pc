package com.pongchamp.pc;

import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.ArrayList;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;

public class HTTPHelper
{
	public String executeHttpPost(ArrayList<NameValuePair> nameValuePairs, String url)
	{
		try
		{
			HttpClient httpclient = new DefaultHttpClient();
			HttpPost httppost = new HttpPost(url);
			httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
			
			HttpResponse httpresponse = httpclient.execute(httppost);
			HttpEntity httpentity = httpresponse.getEntity();
							
			InputStream in = httpentity.getContent();
			BufferedReader read = new BufferedReader(new InputStreamReader(in));
			
			String s = "";
			String line = "";
			
			while ((line = read.readLine()) != null)
			{
				s += line;
			}
			
			return s;
		}
		catch (Exception e)
		{
			e.printStackTrace();
		}
		
		return null;
	}
	
	public String executeHttpGet(ArrayList<NameValuePair> nameValuePairs, String url)
	{
		try
		{
			HttpClient httpclient = new DefaultHttpClient();
			
			url = addParamsToURL(nameValuePairs, url);
			
			HttpGet httpget = new HttpGet(url);
			
			HttpResponse httpresponse = httpclient.execute(httpget);
			HttpEntity httpentity = httpresponse.getEntity();
			
			InputStream in = httpentity.getContent();
			BufferedReader read = new BufferedReader(new InputStreamReader(in));
			
			String s = "";
			String line = "";
			
			while ((line = read.readLine()) != null)
			{
				s += line;
			}
			
			return s;
		}
		catch (Exception e)
		{
			e.printStackTrace();
		}
		
		return null;
	}
	
	private String addParamsToURL(ArrayList<NameValuePair> nameValuePairs, String url)
	{
		url += "?";
		
		for (NameValuePair nameValuePair : nameValuePairs)
		{
			url += nameValuePair.getName() + "=" + nameValuePair.getValue() + "&";
		}
		
		url = url.substring(0, url.length() - 1);
		
		System.out.println(url);
		
		return url;
	}
}
