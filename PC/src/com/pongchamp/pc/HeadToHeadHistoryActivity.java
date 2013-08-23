package com.pongchamp.pc;

import java.net.URL;
import java.util.ArrayList;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;

public class HeadToHeadHistoryActivity extends Activity
{
	PCUser _user = null;
	
	ArrayList<String> _playerNames = null;
	
	String _headtoheadURL = "http://www.pongchamp.com/getheadtohead.php";
	
	LinearLayout _headtoheadLayout;
	
	TextView _headtoheadTitle;
	TextView _headtoheadRecordsTitle;
	TextView _headtoheadRecords;
	TextView _headtoheadResults;
	
	Button _headtoheadPlayGame;
	
	final int ID_PLAY_GAME = 1;
	
	public void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_headtohead_history);
		
		_headtoheadLayout = (LinearLayout) findViewById(R.id.headtohead_layout);
		
		_headtoheadTitle = (TextView) findViewById(R.id.headtohead_title);
		_headtoheadRecordsTitle = (TextView) findViewById(R.id.headtohead_records_title);
		_headtoheadRecords = (TextView) findViewById(R.id.headtohead_records);
		_headtoheadResults = (TextView) findViewById(R.id.headtohead_results);
		
		_headtoheadPlayGame = (Button) findViewById(R.id.headtohead_playgame);
		
		_headtoheadLayout.removeView(_headtoheadPlayGame);
		
		// Set up the loading text.
		_headtoheadRecordsTitle.setText("Loading...");
		_headtoheadRecords.setVisibility(View.INVISIBLE);
		_headtoheadResults.setVisibility(View.INVISIBLE);
		
		new Utilities().setFont(getApplicationContext(),
								_headtoheadTitle,
								_headtoheadRecordsTitle,
								_headtoheadRecords,
								_headtoheadResults);
		
		populateScreen();
	}
	
	// Function to retrieve head-to-head information
	// from the database.
	private void populateScreen()
	{
		Bundle extras = getIntent().getExtras();
		
		_user = new PCUser(extras.getString("ActiveUsername"));
		_playerNames = new ArrayList<String>();
		
		_playerNames.add(extras.getString("TeamOnePlayerOne"));
		_playerNames.add(extras.getString("TeamOnePlayerTwo"));
		_playerNames.add(extras.getString("TeamTwoPlayerOne"));
		_playerNames.add(extras.getString("TeamTwoPlayerTwo"));
		
		new PerformBackgroundTaskGetHistory().execute();
	}
	
	// Function to advance to the game.
	private void onHeadToHeadPlayGamePressed()
	{
		_headtoheadPlayGame.setOnClickListener(new View.OnClickListener()
		{	
			public void onClick(View arg0)
			{
				Bundle extras = getIntent().getExtras();
				
				Intent intent = new Intent(HeadToHeadHistoryActivity.this, PlayGameActivity.class);
				
				intent.putExtras(extras);
				
				HeadToHeadHistoryActivity.this.startActivityForResult(intent, ID_PLAY_GAME);
			}
		});
	}
	
	// Function to sift through the
	// head-to-head results.
	private void calculateResults(ArrayList<NameValuePair> results)
	{
		ArrayList<ArrayList<NameValuePair>> games = new ArrayList<ArrayList<NameValuePair>>();
		ArrayList<NameValuePair> game;
		TextView newResult = null;
		
		int count = 0;
		boolean teamOneWon = false;
		
		final int ID_TEAM_ONE_CUPS = 2;
		final int ID_TEAM_TWO_CUPS = 5;
		final int ID_OTS		   = 6;
		final int ID_DATE		   = 7;
		
		int teamOneWins = 0, teamOneLosses = 0, teamOneOTLosses = 0;
		int teamTwoWins = 0, teamTwoLosses = 0, teamTwoOTLosses = 0;
		
		String[] teamOne = new String[2];
		String[] teamTwo = new String[2];
		
		String record = "", gameResults = "";
		
		teamOne[0] = results.get(0).getValue();
		teamOne[1] = results.get(1).getValue();
		teamTwo[0] = results.get(3).getValue();
		teamTwo[1] = results.get(4).getValue();
		
		// The number here is entirely dependent on
		// the number of columns in the "games" relation.
		// Changing this number will break this function.
		while (!results.isEmpty())
		{
			game = new ArrayList<NameValuePair>(8);
			
			for (int i = 0; i < 8; i++)
			{
				game.add(results.remove(0));
			}
			
			games.add(game);
		}
		
		for (ArrayList<NameValuePair> result : games)
		{
			gameResults = "";
			
			if (Integer.parseInt(result.get(ID_TEAM_ONE_CUPS).getValue()) >
			    Integer.parseInt(result.get(ID_TEAM_TWO_CUPS).getValue()))
			{
				gameResults += result.get(0).getValue() + " and " + result.get(1).getValue() + " def. " +
						       result.get(3).getValue() + " and " + result.get(4).getValue() + " by " +
						       result.get(ID_TEAM_ONE_CUPS).getValue() + " cups";
				
				// Trim the 's' if the victory was by one cup.
				if (Integer.parseInt(result.get(ID_TEAM_ONE_CUPS).getValue()) == 1)
				{
					gameResults = gameResults.substring(0, gameResults.length() - 1);
				}
				
				if (result.get(0).getValue().equals(teamOne[0]) || result.get(0).getValue().equals(teamOne[1]))
				{
					teamOneWins++;
					teamOneWon = true;
				}
				else
				{
					teamTwoWins++;
					teamOneWon = false;
				}
				
				if (Integer.parseInt(result.get(ID_OTS).getValue()) > 0)
				{
					gameResults += " (" + result.get(ID_OTS).getValue() + "OT)";
					
					if (teamOneWon)
					{
						teamTwoOTLosses++;
					}
					else
					{
						teamOneOTLosses++;
					}
				}
				else
				{
					if (teamOneWon)
					{
						teamTwoLosses++;
					}
					else
					{
						teamOneLosses++;
					}
				}
			}
			else
			{
				gameResults += result.get(3).getValue() + " and " + result.get(4).getValue() + " def. " +
						       result.get(0).getValue() + " and " + result.get(1).getValue() + " by " +
						       result.get(ID_TEAM_TWO_CUPS).getValue() + " cups";
				
				// Trim the 's' if the victory was by one cup.
				if (Integer.parseInt(result.get(ID_TEAM_TWO_CUPS).getValue()) == 1)
				{
					gameResults = gameResults.substring(0, gameResults.length() - 1);
				}
				
				if (result.get(3).getValue().equals(teamOne[0]) || result.get(3).getValue().equals(teamOne[1]))
				{
					teamOneWins++;
					teamOneWon = true;
				}
				else
				{
					teamTwoWins++;
					teamOneWon = false;
				}
				
				if (Integer.parseInt(result.get(ID_OTS).getValue()) > 0)
				{
					gameResults += " (" + result.get(ID_OTS).getValue() + "OT)";
					
					if (teamOneWon)
					{
						teamTwoOTLosses++;
					}
					else
					{
						teamOneOTLosses++;
					}
				}
				else
				{
					if (teamOneWon)
					{
						teamTwoLosses++;
					}
					else
					{
						teamOneLosses++;
					}
				}
			}
			
			gameResults += ". " + result.get(ID_DATE).getValue();
			
			newResult = new TextView(getApplicationContext());
			
			newResult.setText(gameResults);
			newResult.setBackgroundColor(Color.parseColor(((count % 2) == 0) ? "#FFE794" : "#FFF2C4"));
			newResult.setTextColor(Color.BLACK);
			newResult.setPadding(10, 5, 10, 5);
			
			new Utilities().setFont(getApplicationContext(), newResult);
			
			_headtoheadLayout.addView(newResult);
			
			count++;
		}
		
		record += teamOne[0] + " and " + teamOne[1] + ": " + teamOneWins + "-" + teamOneLosses + "-" + teamOneOTLosses + "\n";
		record += teamTwo[0] + " and " + teamTwo[1] + ": " + teamTwoWins + "-" + teamTwoLosses + "-" + teamTwoOTLosses;
		
		_headtoheadRecords.setText(record);
		_headtoheadRecords.setTextColor(Color.BLACK);
		_headtoheadRecords.setBackgroundColor(Color.parseColor("#FFE794"));
		_headtoheadRecords.setPadding(10, 5, 10, 5);
	}
	
	// Background function to process the request.
	private class PerformBackgroundTaskGetHistory extends AsyncTask<URL, Integer, String>
	{
		@Override
		protected String doInBackground(URL... arg0)
		{
			String result = null;
			
			Utilities util = new Utilities();
			
			try
			{
				HTTPHelper httpHelper = new HTTPHelper();
				ArrayList<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(5);

				nameValuePairs.add(new BasicNameValuePair("player_one", util.replaceSpacesWithEntities(_playerNames.get(0))));
				nameValuePairs.add(new BasicNameValuePair("player_two", util.replaceSpacesWithEntities(_playerNames.get(1))));
				nameValuePairs.add(new BasicNameValuePair("player_three", util.replaceSpacesWithEntities(_playerNames.get(2))));
				nameValuePairs.add(new BasicNameValuePair("player_four", util.replaceSpacesWithEntities(_playerNames.get(3))));
				
				nameValuePairs.add(new BasicNameValuePair("username", _user.getUsername()));

				result = httpHelper.executeHttpGet(nameValuePairs, _headtoheadURL);
			}
			catch (Exception e)
			{
				e.printStackTrace();
			}

			return result;
		}
		
		protected void onPostExecute(String result)
		{
			if (result != null)
			{
				if (result.equals("null"))
				{
					_headtoheadRecordsTitle.setText("No previous history.");
				}
				else
				{
					ArrayList<NameValuePair> headToHeadResults = new CustomJSONParser().parseJSONString(result);
					
					calculateResults(headToHeadResults);
					
					_headtoheadRecordsTitle.setText("Records:");
					_headtoheadRecords.setVisibility(View.VISIBLE);
					_headtoheadResults.setVisibility(View.VISIBLE);
				}
			}
			else
			{
				_headtoheadRecordsTitle.setText("Error loading history.");
			}
			
			_headtoheadLayout.addView(_headtoheadPlayGame);
			
			onHeadToHeadPlayGamePressed();
		}
	}
}
