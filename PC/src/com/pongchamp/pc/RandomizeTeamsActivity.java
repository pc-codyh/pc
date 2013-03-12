package com.pongchamp.pc;

import java.util.ArrayList;
import java.util.Random;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.graphics.Typeface;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.os.StrictMode;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

public class RandomizeTeamsActivity extends Activity
{
	PCUser _activeUser = null;
	Spinner _spinnerToAdd = null;
	Spinner _spinnerInRaffle = null;
	Button _addToRaffleButton = null;
	Button _randomizeButton = null;
	TextView _playerOne = null;
	TextView _playerTwo = null;
	TextView _playerThree = null;
	TextView _playerFour = null;
	TextView _addToRafflePrompt = null;
	TextView _inRafflePrompt = null;
	TextView _versusPrompt = null;
	
	ArrayAdapter<String> _playersInRaffle = null;
		
	@SuppressLint("NewApi")
	public void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_randomize);
		
		Bundle extras = getIntent().getExtras();
		
		_spinnerToAdd = (Spinner) findViewById(R.id.randomizeteams_spinnerToAdd);
		_spinnerInRaffle = (Spinner) findViewById(R.id.randomizeteams_spinnerInRaffle);
		_addToRaffleButton = (Button) findViewById(R.id.randomizeteams_addPlayerButton);
		_randomizeButton = (Button) findViewById(R.id.randomizeteams_submitRandomization);
		_playerOne = (TextView) findViewById(R.id.randomizeteams_playerOne);
		_playerTwo = (TextView) findViewById(R.id.randomizeteams_playerTwo);
		_playerThree = (TextView) findViewById(R.id.randomizeteams_playerThree);
		_playerFour = (TextView) findViewById(R.id.randomizeteams_playerFour);
		_addToRafflePrompt = (TextView) findViewById(R.id.randomizeteams_add_prompt);
		_inRafflePrompt = (TextView) findViewById(R.id.randomizeteams_in_raffle_prompt);
		_versusPrompt = (TextView) findViewById(R.id.randomizeteams_versus_prompt);
		
		_playersInRaffle = new ArrayAdapter<String>(this, android.R.layout.simple_spinner_item);
		
		String username = extras.getString("ActiveUsername");
		
		_activeUser = new PCUser(username);
		
		loadPlayerToAddSpinner();
		onAddPlayerToRaffleButtonPressed();
		onRandomizeButtonPressed();
		
		new Utilities().setFont(getApplicationContext(),
				                _playerOne,
				                _playerTwo,
				                _playerThree,
				                _playerFour,
				                _addToRafflePrompt,
				                _inRafflePrompt,
				                _versusPrompt);
	}
	
	public void onAddPlayerToRaffleButtonPressed()
	{
		_addToRaffleButton.setOnClickListener(new View.OnClickListener()
		{	
			public void onClick(View v)
			{
				String playerName = _spinnerToAdd.getSelectedItem().toString();
				
				_playersInRaffle.add(playerName);
				_playersInRaffle.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
				
				_spinnerInRaffle.setAdapter(_playersInRaffle);
				_spinnerInRaffle.setSelection(_playersInRaffle.getCount() - 1);
			}
		});
	}
	
	public void onRandomizeButtonPressed()
	{
		_randomizeButton.setOnClickListener(new View.OnClickListener()
		{	
			@SuppressLint("ShowToast")
			public void onClick(View v)
			{
				if (_playersInRaffle.getCount() >= 4)
				{
					resetPlayers();
					randomizeTeams();
				}
				else
				{
					Toast.makeText(getApplicationContext(), R.string.less_than_four_in_raffle, Toast.LENGTH_LONG).show();
				}
			}
		});
	}
	
	private void resetPlayers()
	{
		_playerOne.setText("");
		_playerTwo.setText("");
		_playerThree.setText("");
		_playerFour.setText("");
	}
	
	private void randomizeTeams()
	{
		ArrayList<String> playersInRaffle = new ArrayList<String>(_playersInRaffle.getCount());
		String player = null;
		
		for (int i = 0; i < _playersInRaffle.getCount(); i++)
		{
			player = _playersInRaffle.getItem(i);
			
			playersInRaffle.add(player);			
		}
		
		chooseNumbers(playersInRaffle);
	}
	
	private void chooseNumbers(ArrayList<String> players)
	{
		Random generator = new Random();
		String playerOne, playerTwo, playerThree, playerFour = null;
		int idxOne, idxTwo, idxThree, idxFour;
		int count = players.size();
		
		idxOne = generator.nextInt(count);
		
		idxTwo = generator.nextInt(count);
		
		while (idxTwo == idxOne)
		{
			idxTwo = generator.nextInt(count);
		}
		
		idxThree = generator.nextInt(count);
		
		while (idxThree == idxOne || idxThree == idxTwo)
		{
			idxThree = generator.nextInt(count);
		}
		
		idxFour = generator.nextInt(count);
		
		while (idxFour == idxOne || idxFour == idxTwo || idxFour == idxThree)
		{
			idxFour = generator.nextInt(count);
		}
		
		playerOne = players.get(idxOne);
		playerTwo = players.get(idxTwo);
		playerThree = players.get(idxThree);
		playerFour = players.get(idxFour);
		
		setPlayers(playerOne, playerTwo, playerThree, playerFour);
	}
	
	private void setPlayers(final String pOne, final String pTwo, final String pThree, final String pFour)
	{
		_randomizeButton.setEnabled(false);
		_randomizeButton.setText("");
		
		new CountDownTimer(4000, 1000)
		{
			@Override
			public void onFinish()
			{
				_playerOne.setText(pOne);
				_playerTwo.setText(pTwo);
				_playerThree.setText(pThree);
				_playerFour.setText(pFour);
				
				_randomizeButton.setEnabled(true);
				_randomizeButton.setBackgroundResource(R.drawable.randomize);
			}

			@Override
			public void onTick(long millisUntilFinished)
			{
				long secondsUntilFinished = (millisUntilFinished / 1000);
				
				switch ((int) secondsUntilFinished)
				{
					case 3:
					{
						_randomizeButton.setBackgroundResource(R.drawable.number_three);
					}
						break;
					
					case 2:
					{
						_randomizeButton.setBackgroundResource(R.drawable.number_two);
					}
						break;
					
					case 1:
					{
						_randomizeButton.setBackgroundResource(R.drawable.number_one);
					}
						break;
					
					default:
						break;
				}
			}
		}.start();
	}
	
	public void loadPlayerToAddSpinner()
	{
		Bundle extras = getIntent().getExtras();
		
		ArrayList<String> players = extras.getStringArrayList("Players");
		
		addPlayersToSpinner(players);
	}
	
	private void addPlayersToSpinner(ArrayList<String> players)
	{
		ArrayAdapter<String> adapter = new ArrayAdapter<String>(this, android.R.layout.simple_spinner_item);
		
		for (String player : players)
		{
			adapter.add(player);
		}
		
		adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
		
		_spinnerToAdd.setAdapter(adapter);
	}
}
