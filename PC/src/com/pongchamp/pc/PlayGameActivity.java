package com.pongchamp.pc;

import java.util.ArrayList;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.media.MediaPlayer;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TabHost;
import android.widget.TabHost.TabSpec;
import android.widget.TableRow;
import android.widget.TextView;

public class PlayGameActivity extends Activity
{	
	TabHost _tabHost = null;
	Game _game = null;
		
	Button _teamOnePlayerOneButton = null;
	Button _teamOnePlayerTwoButton = null;
	Button _teamTwoPlayerOneButton = null;
	Button _teamTwoPlayerTwoButton = null;
	TextView _teamOnePlayerOneName = null;
	TextView _teamOnePlayerTwoName = null;
	TextView _teamTwoPlayerOneName = null;
	TextView _teamTwoPlayerTwoName = null;
	
	Button _teamOneCup1 = null;
	Button _teamOneCup2 = null;
	Button _teamOneCup3 = null;
	Button _teamOneCup4 = null;
	Button _teamOneCup5 = null;
	Button _teamOneCup6 = null;
	Button _teamOneCup7 = null;
	Button _teamOneCup8 = null;
	Button _teamOneCup9 = null;
	Button _teamOneCup10 = null;
	
	Button _teamTwoCup1 = null;
	Button _teamTwoCup2 = null;
	Button _teamTwoCup3 = null;
	Button _teamTwoCup4 = null;
	Button _teamTwoCup5 = null;
	Button _teamTwoCup6 = null;
	Button _teamTwoCup7 = null;
	Button _teamTwoCup8 = null;
	Button _teamTwoCup9 = null;
	Button _teamTwoCup10 = null;
	
	PongPlayerDisplay _teamOnePlayerOneDisplay = null;
	PongPlayerDisplay _teamOnePlayerTwoDisplay = null;
	PongPlayerDisplay _teamTwoPlayerOneDisplay = null;
	PongPlayerDisplay _teamTwoPlayerTwoDisplay = null;
	
	TextView _teamOnePlayerOneStatsName = null;
	TextView _teamOnePlayerTwoStatsName = null;
	TextView _teamTwoPlayerOneStatsName = null;
	TextView _teamTwoPlayerTwoStatsName = null;
	
	TextView _teamOnePlayerOneNBAJam = null;
	TextView _teamOnePlayerTwoNBAJam = null;
	TextView _teamTwoPlayerOneNBAJam = null;
	TextView _teamTwoPlayerTwoNBAJam = null;
	
	TextView _teamOnePlayerOneShooting = null;
	TextView _teamOnePlayerTwoShooting = null;
	TextView _teamTwoPlayerOneShooting = null;
	TextView _teamTwoPlayerTwoShooting = null;
	
	TextView _shotHistory = null;
	
	MediaPlayer _heatingUpSound = null;
	MediaPlayer _onFireSound = null;
	
	ArrayList<Button> _teamOneCupButtons = null;
	ArrayList<Button> _teamTwoCupButtons = null;
	
	LinearLayout _gameLayout = null;
	LinearLayout _statsLayout = null;
	
	ArrayList<TableRow> _teamOneRows = null;
	ArrayList<TableRow> _teamTwoRows = null;
	
	Button _endGame = null;
	
	final int ID_TEAMONE_PLAYERONE = 0;
	final int ID_TEAMONE_PLAYERTWO = 1;
	final int ID_TEAMTWO_PLAYERONE = 2;
	final int ID_TEAMTWO_PLAYERTWO = 3;
	
	public void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_playgame);
		
		_tabHost = (TabHost) findViewById(R.id.playgame_tabHost);
		_gameLayout = (LinearLayout) findViewById(R.id.playgame_gameTab);
		_statsLayout = (LinearLayout) findViewById(R.id.playgame_statsTab);
		
		setupRows();
		
		_teamOnePlayerOneButton = (Button) findViewById(R.id.playgame_teamOnePlayerOneButton);
		_teamOnePlayerTwoButton = (Button) findViewById(R.id.playgame_teamOnePlayerTwoButton);
		_teamTwoPlayerOneButton = (Button) findViewById(R.id.playgame_teamTwoPlayerOneButton);
		_teamTwoPlayerTwoButton = (Button) findViewById(R.id.playgame_teamTwoPlayerTwoButton);
		_teamOnePlayerOneName = (TextView) findViewById(R.id.playgame_teamOnePlayerOneName);
		_teamOnePlayerTwoName = (TextView) findViewById(R.id.playgame_teamOnePlayerTwoName);
		_teamTwoPlayerOneName = (TextView) findViewById(R.id.playgame_teamTwoPlayerOneName);
		_teamTwoPlayerTwoName = (TextView) findViewById(R.id.playgame_teamTwoPlayerTwoName);
		
		/* The following Buttons are all the cups individual cups. */
		_teamOneCup1 = (Button) findViewById(R.id.playgame_teamOneCup1);
		_teamOneCup2 = (Button) findViewById(R.id.playgame_teamOneCup2);
		_teamOneCup3 = (Button) findViewById(R.id.playgame_teamOneCup3);
		_teamOneCup4 = (Button) findViewById(R.id.playgame_teamOneCup4);
		_teamOneCup5 = (Button) findViewById(R.id.playgame_teamOneCup5);
		_teamOneCup6 = (Button) findViewById(R.id.playgame_teamOneCup6);
		_teamOneCup7 = (Button) findViewById(R.id.playgame_teamOneCup7);
		_teamOneCup8 = (Button) findViewById(R.id.playgame_teamOneCup8);
		_teamOneCup9 = (Button) findViewById(R.id.playgame_teamOneCup9);
		_teamOneCup10 = (Button) findViewById(R.id.playgame_teamOneCup10);
		
		_teamTwoCup1 = (Button) findViewById(R.id.playgame_teamTwoCup1);
		_teamTwoCup2 = (Button) findViewById(R.id.playgame_teamTwoCup2);
		_teamTwoCup3 = (Button) findViewById(R.id.playgame_teamTwoCup3);
		_teamTwoCup4 = (Button) findViewById(R.id.playgame_teamTwoCup4);
		_teamTwoCup5 = (Button) findViewById(R.id.playgame_teamTwoCup5);
		_teamTwoCup6 = (Button) findViewById(R.id.playgame_teamTwoCup6);
		_teamTwoCup7 = (Button) findViewById(R.id.playgame_teamTwoCup7);
		_teamTwoCup8 = (Button) findViewById(R.id.playgame_teamTwoCup8);
		_teamTwoCup9 = (Button) findViewById(R.id.playgame_teamTwoCup9);
		_teamTwoCup10 = (Button) findViewById(R.id.playgame_teamTwoCup10);
		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
		
		/* The following TextViews are used in the Stats Tab */
		_teamOnePlayerOneStatsName = (TextView) findViewById(R.id.playgame_teamOnePlayerOneStatsName);
		_teamOnePlayerTwoStatsName = (TextView) findViewById(R.id.playgame_teamOnePlayerTwoStatsName);
		_teamTwoPlayerOneStatsName = (TextView) findViewById(R.id.playgame_teamTwoPlayerOneStatsName);
		_teamTwoPlayerTwoStatsName = (TextView) findViewById(R.id.playgame_teamTwoPlayerTwoStatsName);
		
		_teamOnePlayerOneNBAJam = (TextView) findViewById(R.id.playgame_teamOnePlayerOneNBAJam);
		_teamOnePlayerTwoNBAJam = (TextView) findViewById(R.id.playgame_teamOnePlayerTwoNBAJam);
		_teamTwoPlayerOneNBAJam = (TextView) findViewById(R.id.playgame_teamTwoPlayerOneNBAJam);
		_teamTwoPlayerTwoNBAJam = (TextView) findViewById(R.id.playgame_teamTwoPlayerTwoNBAJam);
		
		_teamOnePlayerOneShooting = (TextView) findViewById(R.id.playgame_teamOnePlayerOneShooting);
		_teamOnePlayerTwoShooting = (TextView) findViewById(R.id.playgame_teamOnePlayerTwoShooting);
		_teamTwoPlayerOneShooting = (TextView) findViewById(R.id.playgame_teamTwoPlayerOneShooting);
		_teamTwoPlayerTwoShooting = (TextView) findViewById(R.id.playgame_teamTwoPlayerTwoShooting);
		/* * * * * * * * * * * * * * * * * * * * * * * * * * */
		
		_endGame = (Button) findViewById(R.id.playgame_endGameButton);
		_shotHistory = (TextView) findViewById(R.id.playgame_shotHistory);
		
		_heatingUpSound = MediaPlayer.create(getApplicationContext(), R.raw.heating_up);
		_onFireSound = MediaPlayer.create(getApplicationContext(), R.raw.on_fire);
		
		setupTabs();
		setupLayout();
		setupCupButtons();
		
		Bundle extras = getIntent().getExtras();
		
		setupGame(extras, savedInstanceState);
		setupStatsTabLoad();
		
		new Utilities().setFont(getApplicationContext(),
								_teamOnePlayerOneStatsName,
								_teamOnePlayerTwoStatsName,
								_teamTwoPlayerOneStatsName,
								_teamTwoPlayerTwoStatsName,
								_teamOnePlayerOneNBAJam,
								_teamOnePlayerTwoNBAJam,
								_teamTwoPlayerOneNBAJam,
								_teamTwoPlayerTwoNBAJam,
								_teamOnePlayerOneShooting,
								_teamOnePlayerTwoShooting,
								_teamTwoPlayerOneShooting,
								_teamTwoPlayerTwoShooting,
								_shotHistory,
								_teamOnePlayerOneButton,
								_teamOnePlayerTwoButton,
								_teamTwoPlayerOneButton,
								_teamTwoPlayerTwoButton,
								_teamOnePlayerOneName,
								_teamOnePlayerTwoName,
								_teamTwoPlayerOneName,
								_teamTwoPlayerTwoName);
	}
	
	@Override
	public boolean onCreateOptionsMenu(Menu menu)
	{
		super.onCreateOptionsMenu(menu);
		
		getMenuInflater().inflate(R.menu.activity_menu, menu);
		
		return true;
	}
	
	@Override
	public boolean onOptionsItemSelected(MenuItem item)
	{
		switch (item.getItemId())
		{
			// Options button to remove a cup.
			case R.id.action_menu_remove_cup:
			{
				_game.setRemoveCupInProgress(true);
				
				return true;
			}
			
			default:
				return super.onOptionsItemSelected(item);
		}
	}
	
	@Override
	public void onSaveInstanceState(Bundle savedInstanceState)
	{
		super.onSaveInstanceState(savedInstanceState);
		
		/* Pass the game reference the savedInstanceState so the game
		 * and players can save their variables and be properly
		 * restored. */
		_game.storeVariablesForSaveState(savedInstanceState);
		
		savedInstanceState.putBoolean("gameAlreadyLoaded", true);
	}
	
	@Override
	public void onRestoreInstanceState(Bundle savedInstanceState)
	{
		super.onRestoreInstanceState(savedInstanceState);
		
		_game.reloadVariablesFromSaveState(savedInstanceState);
	}
	
	private void setupRows()
	{
		TableRow teamOneRow1 = (TableRow) findViewById(R.id.playgame_teamOneRow1);
		TableRow teamOneRow2 = (TableRow) findViewById(R.id.playgame_teamOneRow2);
		TableRow teamOneRow3 = (TableRow) findViewById(R.id.playgame_teamOneRow3);
		TableRow teamOneRow4 = (TableRow) findViewById(R.id.playgame_teamOneRow4);
		TableRow teamTwoRow1 = (TableRow) findViewById(R.id.playgame_teamTwoRow1);
		TableRow teamTwoRow2 = (TableRow) findViewById(R.id.playgame_teamTwoRow2);
		TableRow teamTwoRow3 = (TableRow) findViewById(R.id.playgame_teamTwoRow3);
		TableRow teamTwoRow4 = (TableRow) findViewById(R.id.playgame_teamTwoRow4);
		
		_teamOneRows = new ArrayList<TableRow>(4);
		_teamTwoRows = new ArrayList<TableRow>(4);
		
		_teamOneRows.add(teamOneRow1);
		_teamOneRows.add(teamOneRow2);
		_teamOneRows.add(teamOneRow3);
		_teamOneRows.add(teamOneRow4);
		_teamTwoRows.add(teamTwoRow1);
		_teamTwoRows.add(teamTwoRow2);
		_teamTwoRows.add(teamTwoRow3);
		_teamTwoRows.add(teamTwoRow4);
	}
	
	private void setupTabs()
	{
		_tabHost.setup();
		
		TabSpec spec = _tabHost.newTabSpec("TAG1");
		
		spec.setContent(R.id.playgame_gameTab);
		spec.setIndicator(getString(R.string.game_tab));
		_tabHost.addTab(spec);
		
		spec = _tabHost.newTabSpec("TAG2");
		
		spec.setContent(R.id.playgame_statsTab);
		spec.setIndicator(getString(R.string.stats_tab));
		_tabHost.addTab(spec);
		
		spec = _tabHost.newTabSpec("TAG3");
		
		spec.setContent(R.id.playgame_historyTab);
		spec.setIndicator(getString(R.string.history_tab));
		_tabHost.addTab(spec);
	}
	
	private void setupLayout()
	{
		Bundle extras = getIntent().getExtras();
		
		String playerOne = extras.getString("TeamOnePlayerOne");
		String playerTwo = extras.getString("TeamOnePlayerTwo");
		String playerThree = extras.getString("TeamTwoPlayerOne");
		String playerFour = extras.getString("TeamTwoPlayerTwo");
		
		_teamOnePlayerOneDisplay = new PongPlayerDisplay(_teamOnePlayerOneButton, _teamOnePlayerOneName, playerOne);
		_teamOnePlayerTwoDisplay = new PongPlayerDisplay(_teamOnePlayerTwoButton, _teamOnePlayerTwoName, playerTwo);
		_teamTwoPlayerOneDisplay = new PongPlayerDisplay(_teamTwoPlayerOneButton, _teamTwoPlayerOneName, playerThree);
		_teamTwoPlayerTwoDisplay = new PongPlayerDisplay(_teamTwoPlayerTwoButton, _teamTwoPlayerTwoName, playerFour);
	}
	
	private void setupStatsTabLoad()
	{
		_tabHost.setOnTabChangedListener(new TabHost.OnTabChangeListener()
		{	
			public void onTabChanged(String tabId)
			{
				updateCurrentStats(_game.getCurrentStats());
			}
		});
	}
	
	private void updateCurrentStats(ArrayList<ArrayList<String>> currentStats)
	{
		final int ID_NAME = 0;
		final int ID_SHOTS = 1;
		final int ID_HITS = 2;
		final int ID_NBA_JAM = 3;
		
		int id = 0;
		
		ArrayList<String> teamOnePlayerOneStats = currentStats.get(0);
		ArrayList<String> teamOnePlayerTwoStats = currentStats.get(1);
		ArrayList<String> teamTwoPlayerOneStats = currentStats.get(2);
		ArrayList<String> teamTwoPlayerTwoStats = currentStats.get(3);
		
		_teamOnePlayerOneStatsName.setText(teamOnePlayerOneStats.get(ID_NAME));
		_teamOnePlayerTwoStatsName.setText(teamOnePlayerTwoStats.get(ID_NAME));
		_teamTwoPlayerOneStatsName.setText(teamTwoPlayerOneStats.get(ID_NAME));
		_teamTwoPlayerTwoStatsName.setText(teamTwoPlayerTwoStats.get(ID_NAME));
		
		for (ArrayList<String> playerStats : currentStats)
		{
			createShootingPercentageString(id, playerStats.get(ID_SHOTS), playerStats.get(ID_HITS));
			
			updateNBAJamState(id, playerStats.get(ID_NBA_JAM));
			
			id++;
		}
	}
	
	private void createShootingPercentageString(int id, String shotsString, String hitsString)
	{
		String str = null;
		float shootingPercentage = 0;
		int shots = Integer.parseInt(shotsString);
		int hits = Integer.parseInt(hitsString);
		
		if (shots != 0)
		{
			shootingPercentage = ((float) hits / (float) shots);
			shootingPercentage *= 100;
		}
		
		str = String.format("Shooting: %d / %d  (%.2f %%)", hits, shots, shootingPercentage);
		
		switch (id)
		{
			case ID_TEAMONE_PLAYERONE:
			{
				_teamOnePlayerOneShooting.setText(str);
			}
				break;
				
			case ID_TEAMONE_PLAYERTWO:
			{
				_teamOnePlayerTwoShooting.setText(str);
			}
				break;
				
			case ID_TEAMTWO_PLAYERONE:
			{
				_teamTwoPlayerOneShooting.setText(str);
			}
				break;
				
			case ID_TEAMTWO_PLAYERTWO:
			{
				_teamTwoPlayerTwoShooting.setText(str);
			}
				break;
				
			default:
				break;
		}
	}
	
	private void updateNBAJamState(int id, String hitStreakString)
	{
		int streak = Integer.parseInt(hitStreakString);
		String nbaJamState = null;
		
		switch (streak)
		{
			case 2:
			{
				nbaJamState = getString(R.string.heating_up);
			}
				break;
				
			case 3:
			{
				nbaJamState = getString(R.string.on_fire);
			}
				break;
			
			default:
			{
				nbaJamState = "";
			}
				break;
		}
		
		switch (id)
		{
			case ID_TEAMONE_PLAYERONE:
			{
				_teamOnePlayerOneNBAJam.setText(nbaJamState);
			}
				break;
				
			case ID_TEAMONE_PLAYERTWO:
			{
				_teamOnePlayerTwoNBAJam.setText(nbaJamState);
			}
				break;
				
			case ID_TEAMTWO_PLAYERONE:
			{
				_teamTwoPlayerOneNBAJam.setText(nbaJamState);
			}
				break;
				
			case ID_TEAMTWO_PLAYERTWO:
			{
				_teamTwoPlayerTwoNBAJam.setText(nbaJamState);
			}
				break;
				
			default:
				break;
		}
	}
	
	private void setupCupButtons()
	{
		_teamOneCupButtons = new ArrayList<Button>(10);
		_teamTwoCupButtons = new ArrayList<Button>(10);
		
		_teamOneCupButtons.add(_teamOneCup1);
		_teamOneCupButtons.add(_teamOneCup2);
		_teamOneCupButtons.add(_teamOneCup3);
		_teamOneCupButtons.add(_teamOneCup4);
		_teamOneCupButtons.add(_teamOneCup5);
		_teamOneCupButtons.add(_teamOneCup6);
		_teamOneCupButtons.add(_teamOneCup7);
		_teamOneCupButtons.add(_teamOneCup8);
		_teamOneCupButtons.add(_teamOneCup9);
		_teamOneCupButtons.add(_teamOneCup10);
		
		_teamTwoCupButtons.add(_teamTwoCup1);
		_teamTwoCupButtons.add(_teamTwoCup2);
		_teamTwoCupButtons.add(_teamTwoCup3);
		_teamTwoCupButtons.add(_teamTwoCup4);
		_teamTwoCupButtons.add(_teamTwoCup5);
		_teamTwoCupButtons.add(_teamTwoCup6);
		_teamTwoCupButtons.add(_teamTwoCup7);
		_teamTwoCupButtons.add(_teamTwoCup8);
		_teamTwoCupButtons.add(_teamTwoCup9);
		_teamTwoCupButtons.add(_teamTwoCup10);
	}
	
	private void setupGame(Bundle extras, Bundle savedInstanceState)
	{
		ArrayList<PongPlayerDisplay> pongPlayers = new ArrayList<PongPlayerDisplay>(4);
		
		pongPlayers.add(_teamOnePlayerOneDisplay);
		pongPlayers.add(_teamOnePlayerTwoDisplay);
		pongPlayers.add(_teamTwoPlayerOneDisplay);
		pongPlayers.add(_teamTwoPlayerTwoDisplay);
		
		_game = new Game(pongPlayers,
						 _teamOneCupButtons,
					     _teamTwoCupButtons,
						 this.getApplicationContext(),
						 extras,
						 _gameLayout,
						 _teamOneRows,
						 _teamTwoRows,
						 _endGame,
						 _shotHistory,
						 this,
						 _heatingUpSound,
						 _onFireSound);

		_game.startGame();
		
		try
		{
			savedInstanceState.getBoolean("gameAlreadyLoaded");
			
			/* If code reaches here the game has already been loaded
			 * and the pregame odds will not be shown again. */
			savedInstanceState.remove("gameAlreadyLoaded");
		}
		catch (Exception e)
		{
			e.printStackTrace();
			
			/* If code reaches here the game has not yet been loaded
			 * and pregame odds will be shown. */
			showPregameOdds();
		}
	}
	
	public void showPregameOdds()
	{
		AlertDialog.Builder alert = new AlertDialog.Builder(this);
		
		alert.setTitle(R.string.pregame_odds_title);
		
		alert.setPositiveButton("Close", new DialogInterface.OnClickListener()
		{		
			public void onClick(DialogInterface dialog, int which)
			{
				// Do nothing.
			}
		});
		
		LayoutInflater inflater = getLayoutInflater();
				
		View layout = inflater.inflate(R.layout.activity_pregame_odds, (ViewGroup) getCurrentFocus());
		
		editPregameDisplay(layout);
		
		alert.setView(layout);
		alert.show();
	}
	
	private void editPregameDisplay(View layout)
	{
		TextView teamOneNames = (TextView) layout.findViewById(R.id.odds_team_one_names);
		TextView teamTwoNames = (TextView) layout.findViewById(R.id.odds_team_two_names);
		TextView teamOneLine = (TextView) layout.findViewById(R.id.odds_team_one_spread);
		TextView teamTwoLine = (TextView) layout.findViewById(R.id.odds_team_two_spread);
		
		TextView teamOneRecord = (TextView) layout.findViewById(R.id.odds_team_one_record);
		TextView teamTwoRecord = (TextView) layout.findViewById(R.id.odds_team_two_record);
		
		TextView prompt = (TextView) layout.findViewById(R.id.odds_prompt);
		TextView versusPrompt = (TextView) layout.findViewById(R.id.odds_versus_prompt);
		
		double teamOneCupDif, teamOneGamesPlayed, teamTwoGamesPlayed, teamTwoCupDif = 0;
		double teamOneSpread, teamOneTmpSpread, teamOneOdds, teamOneCupAvg,
			   teamTwoSpread, teamTwoTmpSpread, teamTwoOdds, teamTwoCupAvg = 0;
		
		PongPlayer teamOnePlayerOne, teamOnePlayerTwo, teamTwoPlayerOne, teamTwoPlayerTwo = null;
		
		teamOnePlayerOne = _game.getPlayer(ID_TEAMONE_PLAYERONE);
		teamOnePlayerTwo = _game.getPlayer(ID_TEAMONE_PLAYERTWO);
		teamTwoPlayerOne = _game.getPlayer(ID_TEAMTWO_PLAYERONE);
		teamTwoPlayerTwo = _game.getPlayer(ID_TEAMTWO_PLAYERTWO);
		
		teamOneCupDif = (teamOnePlayerOne.getCupDifferential() +
				         teamOnePlayerTwo.getCupDifferential());
		
		teamTwoCupDif = (teamTwoPlayerOne.getCupDifferential() +
						 teamTwoPlayerTwo.getCupDifferential());
		
		teamOneGamesPlayed = (teamOnePlayerOne.getGamesPlayed() +
		         			  teamOnePlayerTwo.getGamesPlayed());
		
		teamTwoGamesPlayed = (teamTwoPlayerOne.getGamesPlayed() +
   			  				  teamTwoPlayerTwo.getGamesPlayed());
		
		if (teamOneGamesPlayed != 0)
		{
			teamOneCupAvg = (teamOneCupDif / teamOneGamesPlayed);
		}
		else
		{
			teamOneCupAvg = 0;
		}
		
		if (teamTwoGamesPlayed != 0)
		{
			teamTwoCupAvg = (teamTwoCupDif / teamTwoGamesPlayed);
		}
		else
		{
			teamTwoCupAvg = 0;
		}
		
		teamOneTmpSpread = ((teamOneCupAvg - teamTwoCupAvg) / 2.0);
		teamTwoTmpSpread = -teamOneTmpSpread;
		
		teamOneSpread = roundToHalf(teamOneTmpSpread);
		teamTwoSpread = -teamOneSpread;
		
		teamOneOdds = calculateOdds(teamOneTmpSpread - teamOneSpread);
		teamTwoOdds = calculateOdds(teamTwoTmpSpread - teamTwoSpread);
		
		teamOneLine.setText("Spread: " + modifySpreadForDisplay(teamOneSpread) + "   Odds: " + teamOneOdds);
		teamTwoLine.setText("Spread: " + modifySpreadForDisplay(teamTwoSpread) + "   Odds: " + teamTwoOdds);
		
		teamOneNames.setText(teamOnePlayerOne.getName() + " and " + teamOnePlayerTwo.getName());
		teamTwoNames.setText(teamTwoPlayerOne.getName() + " and " + teamTwoPlayerTwo.getName());
		
		teamOneRecord.setText(_game.getTeamOneRecord());
		teamTwoRecord.setText(_game.getTeamTwoRecord());
		
		new Utilities().setFont(getApplicationContext(),
								teamOneNames,
								teamTwoNames,
								teamOneLine,
								teamTwoLine,
								teamOneRecord,
								teamTwoRecord,
								prompt,
								versusPrompt);
	}
	
	private double roundToHalf(double x)
	{
		return (double) (Math.round(x * 2.0) / 2.0);
	}
	
	private String modifySpreadForDisplay(double spread)
	{
		if (spread > 0)
		{
			return "-" + spread;
		}
		else if (spread < 0)
		{
			return "+" + (Math.abs(spread));
		}
		else
		{
			return "Pick";
		}
	}
	
	private double calculateOdds(double x)
	{
		if (x <= 0.25 && x > 0.20)
		{
			return 1.76;
		}
		else if (x <= 0.20 && x > 0.15)
		{
			return 1.79;
		}
		else if (x <= 0.15 && x > 0.10)
		{
			return 1.81;
		}
		else if (x <= 0.10 && x > 0.05)
		{
			return 1.85;
		}
		else if (x <= 0.05 && x > 0)
		{
			return 1.88;
		}
		else if (x == 0)
		{
			return 1.91;
		}
		else if (x >= -0.05 && x < 0)
		{
			return 1.94;
		}
		else if (x >= -0.10 && x < -0.05)
		{
			return 1.97;
		}
		else if (x >= -0.15 && x < -0.10)
		{
			return 2.01;
		}
		else if (x >= -0.20 && x < -0.15)
		{
			return 2.03;
		}
		else if (x >= -0.25 && x < -0.20)
		{
			return 2.06;
		}
		
		/* Fail-safe. Code should never reach here. */
		return 1.91;
	}
	
	private void showConfirmationPopupWindow()
	{
		AlertDialog.Builder alert = new AlertDialog.Builder(this);
		
		alert.setPositiveButton("Yes", new DialogInterface.OnClickListener()
		{	
			public void onClick(DialogInterface dialog, int which)
			{
				endActivity();
			}
		});
		
		alert.setNegativeButton("No", new DialogInterface.OnClickListener()
		{	
			public void onClick(DialogInterface dialog, int which)
			{
				// Do nothing (stay in game).
			}
		});
		
		alert.setMessage(R.string.quit_game_confirmation);
		
		alert.show();
	}
	
	private void endActivity()
	{
		Intent intent = new Intent(PlayGameActivity.this, ChooseTeamsActivity.class);
		
		if (_game.getStatsUpdatedSuccessfully())
		{	
			setResult(LoginActivity.RESULT_OK, intent);
		}
		else
		{
			setResult(LoginActivity.RESULT_CANCELED, intent);
		}
		
		finish();
	}
	
	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event)
	{	
		if (keyCode != KeyEvent.KEYCODE_MENU)
		{
			showConfirmationPopupWindow();
		}
		
		return false;
	}
}
