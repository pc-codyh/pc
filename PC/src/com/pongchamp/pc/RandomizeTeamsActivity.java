package com.pongchamp.pc;

import java.util.ArrayList;
import java.util.Random;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.util.SparseBooleanArray;
import android.view.KeyEvent;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.ScrollView;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

public class RandomizeTeamsActivity extends Activity
{
	PCUser _activeUser = null;
	
	Button _randomizeButton = null;
	
	TextView _playerOne = null;
	TextView _playerTwo = null;
	TextView _playerThree = null;
	TextView _playerFour = null;
	TextView _inRafflePrompt = null;
	TextView _versusPrompt = null;
	
	LinearLayout _layout;
	ScrollView _subLayout;
	LinearLayout _subLayoutLinear;
	
	ListView _playersList;
	
	ArrayList<String> _selectedItems;
	
	ArrayAdapter<String> _playersInRaffle = null;
	ArrayList<TextView> _playerTextViews = null;
		
	@SuppressLint("NewApi")
	public void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_randomize);
		
		Bundle extras = getIntent().getExtras();
		
		_randomizeButton = (Button) findViewById(R.id.randomizeteams_submitRandomization);
		
		_playerOne = (TextView) findViewById(R.id.randomizeteams_playerOne);
		_playerTwo = (TextView) findViewById(R.id.randomizeteams_playerTwo);
		_playerThree = (TextView) findViewById(R.id.randomizeteams_playerThree);
		_playerFour = (TextView) findViewById(R.id.randomizeteams_playerFour);
		_inRafflePrompt = (TextView) findViewById(R.id.randomizeteams_in_raffle_prompt);
		_versusPrompt = (TextView) findViewById(R.id.randomizeteams_versus_prompt);
		
		_layout = (LinearLayout) findViewById(R.id.randomizeteams_layout);
		_subLayout = (ScrollView) findViewById(R.id.randomizeteams_sublayout);
		_subLayoutLinear = (LinearLayout) findViewById(R.id.randomizeteams_sublayout_linear);
		
		// Initially hide the visibility of everything
		// except the ListView.
		_layout.removeView(_subLayout);
		
		_playersList = (ListView) findViewById(R.id.randomizeteams_list);
		
		_selectedItems = new ArrayList<String>();
		
		_playersInRaffle = new ArrayAdapter<String>(this, android.R.layout.simple_spinner_dropdown_item);
		
		String username = extras.getString("ActiveUsername");
		
		_activeUser = new PCUser(username);
		
		onRandomizeButtonPressed();
		loadPlayers();
		
		setOnListViewClickListener();
		
		new Utilities().setFont(getApplicationContext(),
				                _playerOne,
				                _playerTwo,
				                _playerThree,
				                _playerFour,
				                _inRafflePrompt,
				                _versusPrompt);
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
	
	public void loadPlayers()
	{
		Toast.makeText(getApplicationContext(), "Choose players to add in the raffle.", Toast.LENGTH_LONG).show();
		
		Bundle extras = getIntent().getExtras();
		
		ArrayList<String> players = extras.getStringArrayList("Players");
		
		addPlayersToList(players);
	}
	
	private void addPlayersToList(ArrayList<String> players)
	{
		ArrayAdapter<String> adapter = new CustomArrayAdapter(this, android.R.layout.simple_list_item_multiple_choice);
		
		for (String player : players)
		{
			adapter.add(player);
		}
		
		adapter.setDropDownViewResource(android.R.layout.simple_list_item_multiple_choice);
		
		_playersList.setAdapter(adapter);
	}
	
	private void setOnListViewClickListener()
	{
		_playersList.setChoiceMode(ListView.CHOICE_MODE_MULTIPLE);
		
		_playersList.setOnItemClickListener(new OnItemClickListener()
		{
			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2, long arg3)
			{
				_selectedItems.clear();
				_playersInRaffle.clear();
				
				SparseBooleanArray a = _playersList.getCheckedItemPositions();
				
				for (int i = 0; i < a.size(); i++)
				{
					if (a.valueAt(i))
					{
						_selectedItems.add(_playersList.getAdapter().getItem(a.keyAt(i)).toString());
					}
				}
				
				for (String p : _selectedItems)
				{
					_playersInRaffle.add(p);
				}
				
				if (_playerTextViews == null)
				{
					_playerTextViews = new ArrayList<TextView>();
				}
				
				for (TextView tv : _playerTextViews)
				{
					_subLayoutLinear.removeView(tv);
				}
				
				_playerTextViews.clear();
				
				TextView playerView;
				int count = 0;
				
				for (String p : _selectedItems)
				{
					playerView = new TextView(getApplicationContext());
					
					playerView.setText(p);
					playerView.setTextColor(Color.BLACK);
					playerView.setPadding(10, 5, 10, 5);
					playerView.setBackgroundColor((count % 2 == 0) ? Color.parseColor("#FFE794") : Color.parseColor("#FFF2C4"));
					
					new Utilities().setFont(getApplicationContext(), playerView);
					
					_playerTextViews.add(playerView);
					
					_subLayoutLinear.addView(playerView, (count + 1));
					
					count++;
				}
			}
		});
	}
	
	private void clearPlayersInRaffle()
	{
		_playersList.clearChoices();
		_selectedItems.clear();
		_playersInRaffle.clear();
		
		_layout.removeAllViews();
		_layout.addView(_playersList);
	}
	
	@Override
	public boolean onCreateOptionsMenu(Menu menu)
	{
		super.onCreateOptionsMenu(menu);
		
		getMenuInflater().inflate(R.menu.randomize_menu, menu);
		
		return true;
	}
	
	@Override
	public boolean onOptionsItemSelected(MenuItem item)
	{
		switch (item.getItemId())
		{
			case R.id.randomize_menu_viewlist:
			{
				_layout.removeAllViews();
				_layout.addView(_playersList);
				
				return true;
			}
			
			case R.id.randomize_menu_randomize:
			{
				_layout.removeAllViews();
				_layout.addView(_subLayout);
				
				return true;
			}
			
			case R.id.randomize_menu_clear:
			{
				clearPlayersInRaffle();
				
				return true;
			}
			
			default:
				return super.onOptionsItemSelected(item);
		}
	}
}
