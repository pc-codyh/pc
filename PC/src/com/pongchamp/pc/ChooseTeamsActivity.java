package com.pongchamp.pc;

import java.util.ArrayList;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

public class ChooseTeamsActivity extends Activity
{
	Spinner _teamOnePlayerOne = null;
	Spinner _teamOnePlayerTwo = null;
	Spinner _teamTwoPlayerOne = null;
	Spinner _teamTwoPlayerTwo = null;
	Button _playGameButton = null;
	TextView _teamOnePrompt = null;
	TextView _teamTwoPrompt = null;
	TextView _currentRulesPrompt = null;
	
	LinearLayout _layout = null;
	
	final String _left = "left";
	final String _right = "right";
	
	final int ID_PLAY_GAME = 1;
	
	public void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_chooseteams);
		
		_teamOnePlayerOne = (Spinner) findViewById(R.id.chooseteams_teamOne_playerOne);
		_teamOnePlayerTwo = (Spinner) findViewById(R.id.chooseteams_teamOne_playerTwo);
		_teamTwoPlayerOne = (Spinner) findViewById(R.id.chooseteams_teamTwo_playerOne);
		_teamTwoPlayerTwo = (Spinner) findViewById(R.id.chooseteams_teamTwo_playerTwo);
		_playGameButton = (Button) findViewById(R.id.chooseteams_playgame);
		
		_teamOnePrompt = (TextView) findViewById(R.id.chooseteams_teamone_prompt);
		_teamTwoPrompt = (TextView) findViewById(R.id.chooseteams_teamtwo_prompt);
		
		_currentRulesPrompt = (TextView) findViewById(R.id.chooseteams_currentrules_prompt);

		_layout = (LinearLayout) findViewById(R.id.chooseteams_layout);
		
		_layout.removeView(_playGameButton);
		
		populateSpinners();
		onPlayGameButtonPressed();
		displayCurrentRules();
		
		new Utilities().setFont(getApplicationContext(),
								_teamOnePrompt,
								_teamTwoPrompt,
								_currentRulesPrompt,
								_playGameButton);
	}
	
	private void displayCurrentRules()
	{
		String str, startingCups, bouncesWorth, bounceInRedemp, nbaJam = "";
		TextView rule = null;
		
		startingCups = getIntent().getExtras().getString("StartingCups").equals(_left) ? "6" : "10";
		bouncesWorth = getIntent().getExtras().getString("BouncesWorth").equals(_left) ? "1" : "2";
		bounceInRedemp = getIntent().getExtras().getString("BounceInRedemption").equals(_left) ? "Yes" : "No";
		nbaJam = getIntent().getExtras().getString("NBAJam").equals(_left) ? "On" : "Off";
		
		for (int i = 0; i < 4; i++)
		{
			rule = new TextView(getApplicationContext());
			
			rule.setPadding(20, 5, 20, 5);
			rule.setTextColor(Color.BLACK);
			
			switch (i)
			{
				case 0:
				{
					str = "Starting Cups: " + startingCups;
				}
					break;
					
				case 1:
				{
					str = "Bounces Worth: " + bouncesWorth;
				}
					break;
					
				case 2:
				{
					str = "Bounce In Redemption Sends Game To OT: " + bounceInRedemp;
				}
					break;
					
				case 3:
				{
					str = "NBA Jam Rule: " + nbaJam;
				}
					break;
					
				default:
				{
					str = "";
				}
					break;
			}
			
			rule.setText(str);
			
			new Utilities().setFont(getApplicationContext(), rule);
			
			_layout.addView(rule);
		}
		
		_layout.addView(_playGameButton);
	}
	
	private void populateSpinners()
	{
		Bundle extras = getIntent().getExtras();
		ArrayList<String> players = extras.getStringArrayList("Players");
		ArrayAdapter<String> adapter = new ArrayAdapter<String>(this, android.R.layout.simple_spinner_item);
		
		for (String player : players)
		{
			adapter.add(player);
		}
		
		adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
		
		_teamOnePlayerOne.setAdapter(adapter);
		_teamOnePlayerTwo.setAdapter(adapter);
		_teamTwoPlayerOne.setAdapter(adapter);
		_teamTwoPlayerTwo.setAdapter(adapter);		
	}
	
	public void onPlayGameButtonPressed()
	{
		_playGameButton.setOnClickListener(new View.OnClickListener()
		{	
			public void onClick(View arg0)
			{
				if (validateTeams())
				{
					startGame();					
				}
				else
				{
					Toast.makeText(getApplicationContext(), R.string.invalid_teams, Toast.LENGTH_LONG).show();
				}
			}
		});
	}
	
	private boolean validateTeams()
	{
		String teamOnePlayerOne = _teamOnePlayerOne.getSelectedItem().toString();
		String teamOnePlayerTwo = _teamOnePlayerTwo.getSelectedItem().toString();
		String teamTwoPlayerOne = _teamTwoPlayerOne.getSelectedItem().toString();
		String teamTwoPlayerTwo = _teamTwoPlayerTwo.getSelectedItem().toString();
		
		boolean valid = true;
		
		if (teamOnePlayerOne.equals(teamOnePlayerTwo) ||
			teamOnePlayerOne.equals(teamTwoPlayerOne) ||
			teamOnePlayerOne.equals(teamTwoPlayerTwo))
		{
			valid = false;
		}
		else if (teamOnePlayerTwo.equals(teamTwoPlayerOne) ||
				 teamOnePlayerTwo.equals(teamTwoPlayerTwo))
		{
			valid = false;
		}
		else if (teamTwoPlayerOne.equals(teamTwoPlayerTwo))
		{
			valid = false;
		}
		
		return valid;
	}
	
	private void startGame()
	{
		Bundle extras = getIntent().getExtras();
		
		Intent intent = new Intent(ChooseTeamsActivity.this, HeadToHeadHistoryActivity.class);
		
		intent.putExtra("TeamOnePlayerOne", _teamOnePlayerOne.getSelectedItem().toString());
		intent.putExtra("TeamOnePlayerTwo", _teamOnePlayerTwo.getSelectedItem().toString());
		intent.putExtra("TeamTwoPlayerOne", _teamTwoPlayerOne.getSelectedItem().toString());
		intent.putExtra("TeamTwoPlayerTwo", _teamTwoPlayerTwo.getSelectedItem().toString());
		
		intent.putExtra("StartingCups", extras.getString("StartingCups"));
		intent.putExtra("BouncesWorth", extras.getString("BouncesWorth"));
		intent.putExtra("BounceInRedemption", extras.getString("BounceInRedemption"));
		intent.putExtra("NBAJam", extras.getString("NBAJam"));
		
		intent.putExtra("ActiveUsername", extras.getString("ActiveUsername"));
		
		ChooseTeamsActivity.this.startActivityForResult(intent, ID_PLAY_GAME);
	}
}
