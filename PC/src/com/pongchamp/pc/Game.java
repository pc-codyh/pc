package com.pongchamp.pc;

import java.net.URL;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;

import android.app.Activity;
import android.content.Context;
import android.media.MediaPlayer;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.Gravity;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TableRow;
import android.widget.TextView;
import android.widget.Toast;

public class Game
{
	PCUser _activeUser = null;
	
	PongPlayerDisplay _teamOnePlayerOneDisplay = null;
	PongPlayerDisplay _teamOnePlayerTwoDisplay = null;
	PongPlayerDisplay _teamTwoPlayerOneDisplay = null;
	PongPlayerDisplay _teamTwoPlayerTwoDisplay = null;
	
	PongPlayer _teamOnePlayerOne = null;
	PongPlayer _teamOnePlayerTwo = null;
	PongPlayer _teamTwoPlayerOne = null;
	PongPlayer _teamTwoPlayerTwo = null;
	
	MediaPlayer _heatingUpSound = null;
	MediaPlayer _onFireSound = null;
	
	String _teamOneRecord = null;
	String _teamTwoRecord = null;
	
	ArrayList<PongPlayer> _players = null;
	
	ArrayList<Button> _teamOneCups = null;
	ArrayList<Button> _teamTwoCups = null;
	ArrayList<Button> _teamOneOvertimeCups = null;
	ArrayList<Button> _teamTwoOvertimeCups = null;
	
	ArrayList<NameValuePair> _eloRatings = null;
	
	Button _endGame = null;
	Activity _activityRef = null;
	
	ArrayList<ArrayList<String>> _currentStats = null;
	
	int _teamOneCupsRemaining = 0;
	int _teamTwoCupsRemaining = 0;
	
	int _overtimeCount = 0;
	
	int _requestCount = 0;
	
	ArrayList<String> _rules = null;
	String _left = "left";
	String _right = "right";
	
	final int ID_TEAM_ONE = 1;
	final int ID_TEAM_TWO = 2;
	
	final int ID_HIT = 0;
	final int ID_MISS = 1;
	final int ID_BOUNCE = 2;
	final int ID_GANG_BANG = 3;
	final int ID_ERROR = 4;
	
	final int ID_STARTING_CUPS = 0;
	final int ID_BOUNCES_WORTH = 1;
	final int ID_BOUNCE_IN_REDEMP = 2;
	final int ID_NBA_JAM = 3;
	
	final int ID_CUP_ROW_1 = 0;
	final int ID_CUP_ROW_2 = 1;
	final int ID_CUP_ROW_3 = 2;
	final int ID_CUP_ROW_4 = 3;
	
	boolean _shotInProgress = false;
	boolean _shouldEnterOvertime = false;
	boolean _removeCupInProgress = false;
	int _cupsLeftToRemove = 0;
	int _currentGangBangAmount = 0;
	
	Context _context = null;
	LinearLayout _gameLayout = null;
	
	ArrayList<TableRow> _teamOneRows = null;
	ArrayList<TableRow> _teamTwoRows = null;
		
	TableRow _centerRow = null;
	TextView _centerSeparator = null;
	
	TextView _shotHistory = null;
	String _shotHistoryString = null;
	
	boolean _statsUpdatedSuccessfully = false;
	int _statsUpdatedCount = 0;
	
	final String _getPreGameStatsURL = "http://www.pongchamp.com/getpregame.php";
	final String _getTeamPreGameStatsURL = "http://www.pongchamp.com/getteampregame.php";
	final String _saveGameURL = "http://www.pongchamp.com/savegame.php";
	
	public Game(ArrayList<PongPlayerDisplay> pongPlayers,
				ArrayList<Button> teamOneCups,
				ArrayList<Button> teamTwoCups,
				Context context,
				Bundle extras,
				LinearLayout gameLayout,
				ArrayList<TableRow> teamOneRows,
				ArrayList<TableRow> teamTwoRows,
				Button endGame,
				TextView shotHistory,
				Activity activityRef,
				MediaPlayer heatingUpSound,
				MediaPlayer onFireSound)
	{
		_context = context;
		_gameLayout = gameLayout;
		_teamOneRows = teamOneRows;
		_teamTwoRows = teamTwoRows;
		_endGame = endGame;
		_shotHistory = shotHistory;
		_activityRef = activityRef;
		
		_heatingUpSound = heatingUpSound;
		_onFireSound = onFireSound;
		
		_rules = new ArrayList<String>(4);
		
		_activeUser = new PCUser(extras.getString("ActiveUsername"));
		
		for (int i = 0; i < pongPlayers.size(); i++)
		{			
			switch (i)
			{
				case 0:
				{
					_teamOnePlayerOneDisplay = pongPlayers.get(i);
					
					_teamOnePlayerOne = new PongPlayer(_teamOnePlayerOneDisplay, 1, this, _context, _activeUser, 0, _heatingUpSound, _onFireSound);
				}
					break;
					
				case 1:
				{
					_teamOnePlayerTwoDisplay = pongPlayers.get(i);
					
					_teamOnePlayerTwo = new PongPlayer(_teamOnePlayerTwoDisplay, 1, this, _context, _activeUser, 1, _heatingUpSound, _onFireSound);
				}
					break;
					
				case 2:
				{
					_teamTwoPlayerOneDisplay = pongPlayers.get(i);
					
					_teamTwoPlayerOne = new PongPlayer(_teamTwoPlayerOneDisplay, 2, this, _context, _activeUser, 2, _heatingUpSound, _onFireSound);
				}
					break;
					
				case 3:
				{
					_teamTwoPlayerTwoDisplay = pongPlayers.get(i);
					
					_teamTwoPlayerTwo = new PongPlayer(_teamTwoPlayerTwoDisplay, 2, this, _context, _activeUser, 3, _heatingUpSound, _onFireSound);
				}
					break;
					
				default:
					break;
			}
		}
		
		_players = new ArrayList<PongPlayer>(4);
		
		_players.add(_teamOnePlayerOne);
		_players.add(_teamOnePlayerTwo);
		_players.add(_teamTwoPlayerOne);
		_players.add(_teamTwoPlayerTwo);
		
		_teamOneCups = teamOneCups;
		_teamTwoCups = teamTwoCups;
		
		_currentStats = new ArrayList<ArrayList<String>>(4);
		
		_teamOneOvertimeCups = new ArrayList<Button>(3);
		_teamTwoOvertimeCups = new ArrayList<Button>(3);
		
		backupOvertimeCups();
		setupCupButtons();
		setupEndGameButton();
		setupRules(extras);
	}
	
	/* The core method of the Game class. Acts as the 'onCreate()' method
	 * for the Game class.
	 */
	public void startGame()
	{
		if (_eloRatings != null)
		{
			_eloRatings = null;
		}
		
		queryInitialStats();
		updateCupsRemaining();
		updateStatsTab();
	}
	
	private void queryInitialStats()
	{
		HTTPHelper httpHelper = new HTTPHelper();
		ArrayList<NameValuePair> nameValuePairs = null;
		CustomJSONParser JSONParser = new CustomJSONParser();
		ArrayList<NameValuePair> preGameStats = null;
		String result = null;
		
		_eloRatings = new ArrayList<NameValuePair>(4);
		
		for (PongPlayer player : _players)
		{
			nameValuePairs = new ArrayList<NameValuePair>(2);
			
			nameValuePairs.add(new BasicNameValuePair("username", _activeUser.getUsername()));
			nameValuePairs.add(new BasicNameValuePair("name", replaceSpacesWithEntities(player.getName())));
			
			result = httpHelper.executeHttpGet(nameValuePairs, _getPreGameStatsURL);
			
			preGameStats = JSONParser.parseJSONString(result);
			
			applyPreGameStats(player, preGameStats);
		}
		
		for (PongPlayer player : _players)
		{
			player.setEloRatings(_eloRatings);
		}
		
		/* Query for team records. */
		nameValuePairs = new ArrayList<NameValuePair>(5);

		nameValuePairs.add(new BasicNameValuePair("username", _activeUser.getUsername()));
		nameValuePairs.add(new BasicNameValuePair("team_one_player_one", replaceSpacesWithEntities(_teamOnePlayerOne.getName())));
		nameValuePairs.add(new BasicNameValuePair("team_one_player_two", replaceSpacesWithEntities(_teamOnePlayerTwo.getName())));
		nameValuePairs.add(new BasicNameValuePair("team_two_player_one", replaceSpacesWithEntities(_teamTwoPlayerOne.getName())));
		nameValuePairs.add(new BasicNameValuePair("team_two_player_two", replaceSpacesWithEntities(_teamTwoPlayerTwo.getName())));
		
		result = httpHelper.executeHttpGet(nameValuePairs, _getTeamPreGameStatsURL);
		
		preGameStats = JSONParser.parseJSONString(result);
		
		applyPreGameTeamRecords(preGameStats);
	}
	
	private void applyPreGameTeamRecords(ArrayList<NameValuePair> teamRecords)
	{
		String record = "";
		
		for (NameValuePair nameValuePair : teamRecords)
		{
			if (nameValuePair.getName().equals("wins") || nameValuePair.getName().equals("losses"))
			{
				record += nameValuePair.getValue() + "-";
			}
			
			if (nameValuePair.getName().equals("ot_losses"))
			{
				record += nameValuePair.getValue();
			}
			
			if (nameValuePair.getName().equals("player_one") || nameValuePair.getName().equals("player_two"))
			{
				if (_teamOnePlayerOne.getName().equals(nameValuePair.getValue()) || _teamOnePlayerTwo.getName().equals(nameValuePair.getValue()))
				{
					if (_teamOneRecord == null)
					{
						_teamOneRecord = record;
					}
					
					record = "";
				}
				
				if (_teamTwoPlayerOne.getName().equals(nameValuePair.getValue()) || _teamTwoPlayerTwo.getName().equals(nameValuePair.getValue()))
				{
					if (_teamTwoRecord == null)
					{
						_teamTwoRecord = record;
					}
					
					record = "";
				}
			}
		}
		
		if (_teamOneRecord == null)
		{
			_teamOneRecord = "0-0-0";
		}
		
		if (_teamTwoRecord == null)
		{
			_teamTwoRecord = "0-0-0";
		}
	}
	
	private void applyPreGameStats(PongPlayer player, ArrayList<NameValuePair> preGameStats)
	{
		final int ID_CUP_DIF = 0;
		final int ID_ELO_RATING = 1;
		final int ID_WINS = 2;
		final int ID_LOSSES = 3;
		final int ID_OT_LOSSES = 4;
		final int ID_RANK = 5;
		
		int gamesPlayed = 0;
		
		_eloRatings.add(new BasicNameValuePair(Integer.toString(player.getTeamID()), preGameStats.get(ID_ELO_RATING).getValue()));
		
		player.setCupDifferential(Integer.parseInt(preGameStats.get(ID_CUP_DIF).getValue()));
		player.setEloRating(Float.parseFloat(preGameStats.get(ID_ELO_RATING).getValue()));
		
		gamesPlayed = (Integer.parseInt(preGameStats.get(ID_WINS).getValue()) + 
					   Integer.parseInt(preGameStats.get(ID_LOSSES).getValue()) +
					   Integer.parseInt(preGameStats.get(ID_OT_LOSSES).getValue()));
		
		player.setGamesPlayed(gamesPlayed);
		player.setRank(Integer.parseInt(preGameStats.get(ID_RANK).getValue()));
		
		setRankDisplays();
	}
	
	private void setRankDisplays()
	{
		Button btn = null;
		
		for (PongPlayer player : _players)
		{
			btn = player.getDisplay().getButton();
			
			btn.setText(Integer.toString(player.getRank()));
		}
	}
	
	private void setupEndGameButton()
	{
		_endGame.setOnClickListener(new View.OnClickListener()
		{	
			public void onClick(View arg0)
			{
				if (_statsUpdatedSuccessfully == false)
				{
					if (gameIsOver())
					{
						processFinalStats();
					}
					else
					{
						Toast.makeText(_context, _context.getString(R.string.game_not_over_warning), Toast.LENGTH_LONG).show();
					}
				}
				else
				{
					_activityRef.finish();
				}
			}
		});
	}
	
	private boolean gameIsOver()
	{
		return (_teamOneCupsRemaining == 0 || _teamTwoCupsRemaining == 0);
	}
	
	private void backupOvertimeCups()
	{
		for (Button cup : _teamOneCups)
		{
			_teamOneOvertimeCups.add(cup);
			
			if (_teamOneOvertimeCups.size() == 3)
			{
				break;
			}
		}
		
		for (Button cup : _teamTwoCups)
		{
			_teamTwoOvertimeCups.add(cup);
			
			if (_teamTwoOvertimeCups.size() == 3)
			{
				break;
			}
		}
	}
	
	private void setupRules(Bundle extras)
	{
		_rules.add(extras.getString("StartingCups"));
		_rules.add(extras.getString("BouncesWorth"));
		_rules.add(extras.getString("BounceInRedemption"));
		_rules.add(extras.getString("NBAJam"));
		
		if (_rules.get(ID_STARTING_CUPS).equals(_left))
		{
			_teamOneCupsRemaining = _teamTwoCupsRemaining = 6;
			
			setStartingCupsToSix();
		}
		else
		{
			_teamOneCupsRemaining = _teamTwoCupsRemaining = 10;
		}
	}
	
	private void setStartingCupsToSix()
	{
		int count = 4;
		
		for (Button cup : _teamOneCups)
		{
			removeCup(cup);
			
			count--;
			
			if (count == 0)
			{
				break;
			}
		}
		
		count = 4;
		
		for (Button cup : _teamTwoCups)
		{
			removeCup(cup);
			
			count--;
			
			if (count == 0)
			{
				break;
			}
		}
		
		rerackCups(ID_TEAM_ONE);
		rerackCups(ID_TEAM_TWO);
	}
	
	public void playerIconPressed(Button pressedIcon)
	{
		for (PongPlayer player : _players)
		{
			if (player.getDisplay().getButton() != pressedIcon)
			{
				player.setButtonPressed(player.getDisplay().getButton(), false);
			}
		}
	}
	
	private void updateCupsRemaining()
	{
		_teamOnePlayerOne.setCupsRemaining(_teamTwoCupsRemaining, _teamOneCupsRemaining);
		_teamOnePlayerTwo.setCupsRemaining(_teamTwoCupsRemaining, _teamOneCupsRemaining);
		_teamTwoPlayerOne.setCupsRemaining(_teamOneCupsRemaining, _teamTwoCupsRemaining);
		_teamTwoPlayerTwo.setCupsRemaining(_teamOneCupsRemaining, _teamTwoCupsRemaining);
	}
	
	/* Includes Hits, Bounces, and Gang-Bangs. */
	private boolean checkForActiveShooter(int teamID)
	{
		boolean activeShooter = false;
		
		for (PongPlayer player : _players)
		{
			if (player.isActiveShooter() &&
				player.getTeamID() == teamID &&
			    player.getActiveShotType() != ID_ERROR)
			{
				activeShooter = true;
				
				if (!_shotInProgress)
				{
					_cupsLeftToRemove = 1;
				}
				
				if (player.getActiveShotType() == ID_HIT && !_shotInProgress)
				{
					player.hit(true);
				}
				
				if (player.getActiveShotType() == ID_BOUNCE && !_shotInProgress)
				{
					player.bounce();
					
					/* If bounces are worth two cups. */
					if (_rules.get(ID_BOUNCES_WORTH).equals(_right))
					{
						if (teamID == ID_TEAM_ONE)
						{
							_cupsLeftToRemove = Math.min(_teamTwoCupsRemaining, 2);
						}
						else if (teamID == ID_TEAM_TWO)
						{
							_cupsLeftToRemove = Math.min(_teamOneCupsRemaining, 2);
						}
						
						_shotInProgress = true;
						
						disableAllPlayers();
						
						if (_cupsLeftToRemove > 1)
						{
							Toast.makeText(_context, R.string.select_two_cups, Toast.LENGTH_LONG).show();
						}
					}
				}
				
				if (player.getActiveShotType() == ID_GANG_BANG && !_shotInProgress)
				{
					player.gangBang();
					
					for (PongPlayer partner : _players)
					{
						if (partner.getTeamID() == player.getTeamID() && partner != player)
						{
							partner.gangBang();
						}
					}
					
					_cupsLeftToRemove = _currentGangBangAmount;
					_currentGangBangAmount = 0;
					
					_shotInProgress = true;
					
					disableAllPlayers();
					
					if (_cupsLeftToRemove > 2)
					{
						Toast.makeText(_context,
								       "Select the remaining " + (_cupsLeftToRemove - 1) + " cups removed.",
								       Toast.LENGTH_LONG).show();
					}
					else if (_cupsLeftToRemove == 2)
					{
						Toast.makeText(_context, R.string.select_two_cups, Toast.LENGTH_LONG).show();
					}
				}
				
				break;
			}
		}
		
		return activeShooter;
	}
	
	private boolean checkForActiveError(int teamID)
	{
		boolean activeError = false;
		
		for (PongPlayer player : _players)
		{
			if (player.isActiveShooter() &&
				player.getTeamID() == teamID &&
				player.getActiveShotType() == ID_ERROR)
			{
				player.error();
				
				activeError = true;
				
				break;
			}
		}
		
		return activeError;
	}
	
	public void clearActiveShooters()
	{
		playerIconPressed(null);
	}
	
	private void removeCup(Button cup)
	{
		cup.setVisibility(View.INVISIBLE);
		cup.setEnabled(false);
		
		if (_cupsLeftToRemove == 0)
		{
			_shotInProgress = false;
			
			clearActiveShooters();
			enableAllPlayers();
		}
		
		rerackCups(ID_TEAM_ONE);
		rerackCups(ID_TEAM_TWO);
		
		if (gameShouldEnterOvertime())
		{
			setupOvertime();
		}
	}
	
	private boolean gameShouldEnterOvertime()
	{
		return ((_teamOneCupsRemaining == 0 && _teamTwoCupsRemaining == 0) ||
				(_shouldEnterOvertime && _cupsLeftToRemove == 0));
	}
	
	private void setupOvertime()
	{
		TableRow row = null;
		
		for (Button cup : _teamOneCups)
		{
			cup.setEnabled(false);
			cup.setVisibility(View.INVISIBLE);
		}
		
		for (Button cup : _teamTwoCups)
		{
			cup.setEnabled(false);
			cup.setVisibility(View.INVISIBLE);
		}
		
		_shouldEnterOvertime = false;
		
		_overtimeCount++;
		
		showOvertimeMessage(_overtimeCount);
		
		for (TableRow tableRow : _teamOneRows)
		{
			tableRow.removeAllViews();
		}
		
		for (TableRow tableRow : _teamTwoRows)
		{
			tableRow.removeAllViews();
		}
		
		for (Button cup : _teamOneOvertimeCups)
		{
			row = _teamOneRows.get(ID_CUP_ROW_4);
			
			row.addView(cup);
			
			cup.setVisibility(View.VISIBLE);
			cup.setEnabled(true);
		}
		
		for (Button cup : _teamTwoOvertimeCups)
		{
			row = _teamTwoRows.get(ID_CUP_ROW_4);
			
			row.addView(cup);
			
			cup.setVisibility(View.VISIBLE);
			cup.setEnabled(true);
		}
		
		for (PongPlayer player : _players)
		{
			player.setHasEnteredOvertime(true);
		}
		
		_teamOneCupsRemaining = 3;
		_teamTwoCupsRemaining = 3;
		
		rerackCups(ID_TEAM_ONE);
		rerackCups(ID_TEAM_TWO);
		
		addOvertimeStringToShotHistory();
	}
	
	private void addOvertimeStringToShotHistory()
	{
		String str = null;
		
		str = _context.getString(R.string.shot_history_overtime) + _overtimeCount;
		str += "\n--------------------------------------------------\n";
		
		concatenateShotHistory(str);
	}
	
	private void showOvertimeMessage(int overtimeCount)
	{
		String msg = null;
		
		switch (overtimeCount)
		{
			case 1:
			{
				msg = _context.getString(R.string.single_overtime);
			}
				break;
				
			case 2:
			{
				msg = _context.getString(R.string.double_overtime);
			}
				break;
				
			case 3:
			{
				msg = _context.getString(R.string.triple_overtime);
			}
				break;
				
			case 4:
			{
				msg = _context.getString(R.string.quadruple_overtime);
			}
				break;
				
			default:
			{
				msg = String.format("Overtime %d", overtimeCount);
			}
				break;
		}
		
		Toast.makeText(_context, msg, Toast.LENGTH_LONG).show();
	}
	
	private void setupCupButtons()
	{
		for (final Button btn : _teamOneCups)
		{
			btn.setOnClickListener(new View.OnClickListener()
			{	
				public void onClick(View arg0)
				{
					if (checkForActiveShooter(ID_TEAM_TWO))
					{
						///////////////////////
						// Achievement Stuff //
						///////////////////////
						
						// (14) Bitch Cup [Hit the middle cup first on a ten-cup rack]
						if ((btn == _teamOneCups.get(4)) && (_teamOneCupsRemaining == 10))
						{
							// If this is the middle cup on a ten rack.
							for (PongPlayer player : _players)
							{
								if (player.isActiveShooter())
								{
									player.updateBitchCup();
								}
							}
						}
						
						_cupsLeftToRemove--;
						_teamOneCupsRemaining--;
						
						removeCup(btn);
					}
					else if (checkForActiveError(ID_TEAM_ONE))
					{
						_teamOneCupsRemaining--;
						
						removeCup(btn);
					}
					else if (_removeCupInProgress)
					{
						_removeCupInProgress = false;
						_teamOneCupsRemaining--;
						
						removeCup(btn);
					}
					
					updateCupsRemaining();
					updateStatsTab();
				}
			});
		}
		
		for (final Button btn : _teamTwoCups)
		{
			btn.setOnClickListener(new View.OnClickListener()
			{	
				public void onClick(View arg0)
				{
					if (checkForActiveShooter(ID_TEAM_ONE))
					{
						///////////////////////
						// Achievement Stuff //
						///////////////////////
						
						// (14) Bitch Cup [Hit the middle cup first on a ten-cup rack]
						if ((btn == _teamTwoCups.get(4)) && (_teamTwoCupsRemaining == 10))
						{
							// If this is the middle cup on a ten rack.
							for (PongPlayer player : _players)
							{
								if (player.isActiveShooter())
								{
									player.updateBitchCup();
								}
							}
						}
							
						_cupsLeftToRemove--;
						_teamTwoCupsRemaining--;
						
						removeCup(btn);
					}
					else if (checkForActiveError(ID_TEAM_TWO))
					{
						_teamTwoCupsRemaining--;
						
						removeCup(btn);
					}
					else if (_removeCupInProgress)
					{
						_removeCupInProgress = false;
						_teamTwoCupsRemaining--;
						
						removeCup(btn);
					}
					
					updateCupsRemaining();
					updateStatsTab();
				}
			});
		}
	}
	
	// Function to return the amount of cups made
	// by a team member's partner.
	public int getPartnerCups(int partnerId)
	{
		switch (partnerId)
		{
			case 0:
			{
				return _players.get(1).getHits();
			}
			
			case 1:
			{
				return _players.get(0).getHits();
			}
			
			case 2:
			{
				return _players.get(3).getHits();
			}
			
			case 3:
			{
				return _players.get(2).getHits();
			}
			
			default:
				return -1;
		}
	}
	
	private void disableAllPlayers()
	{
		for (PongPlayer player : _players)
		{
			player.getDisplay().getButton().setEnabled(false);
		}
	}
	
	private void enableAllPlayers()
	{
		for (PongPlayer player : _players)
		{
			player.getDisplay().getButton().setEnabled(true);
		}
	}
	
	public int getCupsRemaining(int teamID)
	{
		int count = 0;
		
		if (teamID == ID_TEAM_ONE)
		{
			for (Button cup : _teamOneCups)
			{
				if (cup.getVisibility() == View.VISIBLE)
				{
					count++;
				}
			}
		}
		else if (teamID == ID_TEAM_TWO)
		{
			for (Button cup : _teamTwoCups)
			{
				if (cup.getVisibility() == View.VISIBLE)
				{
					count++;
				}
			}
		}
		
		return count;
	}
	
	private void rerackCups(int teamID)
	{
		ArrayList<Button> remainingCups = new ArrayList<Button>();
		TableRow rowFour = null;
		TableRow rowThree = null;
		TableRow rowTwo = null;
		int runningCount = 0;
		
		if (teamID == ID_TEAM_ONE)
		{
			rowFour = _teamOneRows.get(ID_CUP_ROW_4);
			rowThree = _teamOneRows.get(ID_CUP_ROW_3);
			rowTwo = _teamOneRows.get(ID_CUP_ROW_2);
		}
		else
		{
			rowFour = _teamTwoRows.get(ID_CUP_ROW_4);
			rowThree = _teamTwoRows.get(ID_CUP_ROW_3);
			rowTwo = _teamTwoRows.get(ID_CUP_ROW_2);
		}
		
		/* Rerack to six-cup triangle. */
		if (getCupsRemaining(teamID) == 6)
		{
			if (teamID == ID_TEAM_ONE)
			{
				for (Button cup : _teamOneCups)
				{
					if (cup.getVisibility() == View.INVISIBLE)
					{
						_gameLayout.removeView(cup);
					}
					else
					{
						remainingCups.add(cup);
					}
				}
				
				for (TableRow row : _teamOneRows)
				{
					row.removeAllViews();
				}
				
				for (int i = 0; i < 3; i++)
				{
					rowFour.addView(remainingCups.get(runningCount));
					
					runningCount++;
				}
				
				for (int i = 0; i < 2; i++)
				{
					rowThree.addView(remainingCups.get(runningCount));
					
					runningCount++;
				}
				
				rowTwo.addView(remainingCups.get(runningCount));
			}
			else if (teamID == ID_TEAM_TWO)
			{
				for (Button cup : _teamTwoCups)
				{
					if (cup.getVisibility() == View.INVISIBLE)
					{
						_gameLayout.removeView(cup);
					}
					else
					{
						remainingCups.add(cup);
					}
				}

				for (TableRow row : _teamTwoRows)
				{
					row.removeAllViews();
				}
				
				for (int i = 0; i < 3; i++)
				{
					rowFour.addView(remainingCups.get(runningCount));
					
					runningCount++;
				}
				
				for (int i = 0; i < 2; i++)
				{
					rowThree.addView(remainingCups.get(runningCount));
					
					runningCount++;
				}
				
				rowTwo.addView(remainingCups.get(runningCount));
			}
		}
		/* Rerack to three-cup triangle. */
		else if (getCupsRemaining(teamID) == 3)
		{
			if (teamID == ID_TEAM_ONE)
			{
				for (Button cup : _teamOneCups)
				{
					if (cup.getVisibility() == View.INVISIBLE)
					{
						_gameLayout.removeView(cup);
					}
					else
					{
						remainingCups.add(cup);
					}
				}
				
				for (TableRow row : _teamOneRows)
				{
					row.removeAllViews();
				}
				
				for (int i = 0; i < 2; i++)
				{
					rowFour.addView(remainingCups.get(runningCount));
					
					runningCount++;
				}
				
				rowThree.addView(remainingCups.get(runningCount));
			}
			else if (teamID == ID_TEAM_TWO)
			{
				for (Button cup : _teamTwoCups)
				{
					if (cup.getVisibility() == View.INVISIBLE)
					{
						_gameLayout.removeView(cup);
					}
					else
					{
						remainingCups.add(cup);
					}
				}

				for (TableRow row : _teamTwoRows)
				{
					row.removeAllViews();
				}
				
				for (int i = 0; i < 2; i++)
				{
					rowFour.addView(remainingCups.get(runningCount));
					
					runningCount++;
				}
				
				rowThree.addView(remainingCups.get(runningCount));
			}
		}
		/* Center the final cup. */
		else if (getCupsRemaining(teamID) == 1)
		{
			if (teamID == ID_TEAM_ONE)
			{
				for (Button cup : _teamOneCups)
				{
					if (cup.getVisibility() == View.INVISIBLE)
					{
						_gameLayout.removeView(cup);
					}
					else
					{
						remainingCups.add(cup);
					}
				}
				
				for (TableRow row : _teamOneRows)
				{
					row.removeAllViews();
				}
				
				rowFour.addView(remainingCups.get(runningCount));
			}
			else if (teamID == ID_TEAM_TWO)
			{
				for (Button cup : _teamTwoCups)
				{
					if (cup.getVisibility() == View.INVISIBLE)
					{
						_gameLayout.removeView(cup);
					}
					else
					{
						remainingCups.add(cup);
					}
				}

				for (TableRow row : _teamTwoRows)
				{
					row.removeAllViews();
				}

				rowFour.addView(remainingCups.get(runningCount));
			}
		}
	}
	
	public void setCurrentGangBangAmount(int count, int teamID)
	{
		_currentGangBangAmount = count;
		
		switch (teamID)
		{
			case ID_TEAM_ONE:
			{
				if (_currentGangBangAmount > _teamTwoCupsRemaining)
				{
					_currentGangBangAmount = _teamTwoCupsRemaining;
				}
				else if (_currentGangBangAmount < 0)
				{
					_currentGangBangAmount = 1;
				}
			}
				break;
				
			case ID_TEAM_TWO:
			{
				if (_currentGangBangAmount > _teamOneCupsRemaining)
				{
					_currentGangBangAmount = _teamOneCupsRemaining;
				}
				else if (_currentGangBangAmount < 0)
				{
					_currentGangBangAmount = 1;
				}
			}
				break;
				
			default:
				break;
		}
	}
	
	public ArrayList<String> getRules()
	{
		return _rules;
	}
	
	public void updateStatsTab()
	{
		ArrayList<ArrayList<String>> currentStats = new ArrayList<ArrayList<String>>(4);
		ArrayList<String> playerStats = null;
		
		for (PongPlayer player : _players)
		{
			playerStats = new ArrayList<String>(4);
			
			playerStats.add(player.getDisplay().getTextView().getText().toString());
			playerStats.add(Integer.toString(player.getShots()));
			playerStats.add(Integer.toString(player.getHits()));
			playerStats.add(Integer.toString(player.getCurrentHitStreak(true)));
			
			currentStats.add(playerStats);
		}
		
		_currentStats = currentStats;
	}
	
	public ArrayList<ArrayList<String>> getCurrentStats()
	{
		return _currentStats;
	}
	
	public void bounceInRedemption()
	{
		_shouldEnterOvertime = true;
	}
	
	private String addAchievementToString(int id)
	{
		switch (id)
		{
			case 0:
			{
				return "Sharpshooter";
			}
			
			case 1:
			{
				return "Michael Jordan";
			}
			
			case 2:
			{
				return "Can I Buy A Vowel?";
			}
			
			case 3:
			{
				return "Bankruptcy";
			}
			
			case 4:
			{
				return "Comeback Kill";
			}
			
			case 5:
			{
				return "Heartbreak City";
			}
			
			case 6:
			{
				return "Caught With Their Pants Down";
			}
			
			case 7:
			{
				return "Porn Star";
			}
			
			case 8:
			{
				return "Stevie Wonder";
			}
			
			case 9:
			{
				return "Perfection";
			}
			
			case 10:
			{
				return "Down But Not Out";
			}
			
			case 12:
			{
				return "Bill Buckner";
			}
			
			case 13:
			{
				return "Bitch Cup";
			}
			
			case 14:
			{
				return "Marathon";
			}
			
			case 15:
			{
				return "First Degree Murder";
			}
			
			case 16:
			{
				return "Skunked";
			}
			
			default:
				return "";
		}
	}
	
	public void updatePartnerHeartbreakCityStatus()
	{
		for (PongPlayer player : _players)
		{
			player.updateHeartbreakCityStatus();
		}
	}
	
	private void showAchievements()
	{
		int[] achievements = null;
		boolean gotAchievement = false;
		
		_shotHistory.setGravity(Gravity.LEFT);
		
		_shotHistoryString = "Achievements Earned\n-----------------------------\n\n";
		
		for (PongPlayer player : _players)
		{
			gotAchievement = false;
			
			achievements = player.getAchievements();
			
			_shotHistoryString += player.getName() + ": ";
			
			for (int i = 0; i < achievements.length; i++)
			{
				if (achievements[i] > 0)
				{
					gotAchievement = true;
					
					_shotHistoryString += addAchievementToString(i) + ", ";
					
					// If an unlockable achievement is earned, don't
					// add it to the list.
					if (addAchievementToString(i).equals(""))
					{
						_shotHistoryString = _shotHistoryString.substring(0, _shotHistoryString.length() - 2);
					}
				}
			}
			
			if (gotAchievement)
			{
				_shotHistoryString = _shotHistoryString.substring(0, _shotHistoryString.length() - 2);
			}
			
			_shotHistoryString += "\n\n";
		}
		
		_shotHistoryString += "*Note: Unlockable achievements do not appear here. They can be viewed on the website.";
		
		_shotHistory.setText(_shotHistoryString);
	}
	
	private void processFinalStats()
	{
		for (PongPlayer player : _players)
		{
			player.processFinalStats();
		}
	}
	
	public void saveGameResult()
	{
		new PerformBackgroundTaskSaveGameResult().execute();
	}
	
	private String replaceSpacesWithEntities(String str)
	{
		return str.replace(" ", "%20");
	}
	
	public void updateStatsUpdatedCount()
	{
		_statsUpdatedCount++;
		
		if (_statsUpdatedCount == 4)
		{
			_statsUpdatedSuccessfully = true;
		}
	}
	
	public PongPlayer getPlayer(int id)
	{
		PongPlayer player;
		id++;
		
		switch (id)
		{
			case 1:
			{
				player = _teamOnePlayerOne;
			}
				break;
				
			case 2:
			{
				player = _teamOnePlayerTwo;
			}
				break;
				
			case 3:
			{
				player = _teamTwoPlayerOne;
			}
				break;
				
			case 4:
			{
				player = _teamTwoPlayerTwo;
			}
				break;
			
			default:
			{
				player = null;
			}
				break;
		}
		
		return player;
	}
	
	public void concatenateShotHistory(String str)
	{
		if (_shotHistoryString == null)
		{
			_shotHistoryString = "";
		}
		
		_shotHistoryString += str;
		
		_shotHistory.setText(_shotHistoryString);
	}
	
	public boolean getStatsUpdatedSuccessfully()
	{
		return _statsUpdatedSuccessfully;
	}
	
	public int getOvertimeCount()
	{
		return _overtimeCount;
	}
	
	public String getTeamOneRecord()
	{
		return _teamOneRecord;
	}
	
	public String getTeamTwoRecord()
	{
		return _teamTwoRecord;
	}
	
	private class PerformBackgroundTaskSaveGameResult extends AsyncTask<URL, Integer, String>
	{
		@Override
		protected String doInBackground(URL... params)
		{
			HTTPHelper httpHelper = new HTTPHelper();
			ArrayList<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(9);
			
			nameValuePairs.add(new BasicNameValuePair("username", _activeUser.getUsername()));
			nameValuePairs.add(new BasicNameValuePair("team_one_player_one", _teamOnePlayerOne.getName()));
			nameValuePairs.add(new BasicNameValuePair("team_one_player_two", _teamOnePlayerTwo.getName()));
			nameValuePairs.add(new BasicNameValuePair("team_one_cups_remaining", Integer.toString(_teamOneCupsRemaining)));
			nameValuePairs.add(new BasicNameValuePair("team_two_player_one", _teamTwoPlayerOne.getName()));
			nameValuePairs.add(new BasicNameValuePair("team_two_player_two", _teamTwoPlayerTwo.getName()));
			nameValuePairs.add(new BasicNameValuePair("team_two_cups_remaining", Integer.toString(_teamTwoCupsRemaining)));
			nameValuePairs.add(new BasicNameValuePair("number_of_ots", Integer.toString(_overtimeCount)));
			
			Calendar calendar = Calendar.getInstance();
			Date date = calendar.getTime();
			
			SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
			String dateString = format.format(date);
			
			nameValuePairs.add(new BasicNameValuePair("date", dateString));
			
			httpHelper.executeHttpPost(nameValuePairs, _saveGameURL);
			
			return null;
		}
		
		protected void onPostExecute(String result)
		{
			if (_statsUpdatedSuccessfully)
			{
				Toast.makeText(_context, R.string.stats_updated_successfully, Toast.LENGTH_LONG).show();
				
				_endGame.setBackgroundResource(R.drawable.exit);
				
				showAchievements();
			}
			else
			{
				Toast.makeText(_context, R.string.stats_updated_failure, Toast.LENGTH_LONG).show();
			}
		}
	}
	
	public void incrementRequestCount()
	{
		_requestCount++;
	}
	
	public void setRequestCount(int count)
	{
		_requestCount = count;
	}
	
	public int getRequestCount()
	{
		return _requestCount;
	}
	
	public void setRemoveCupInProgress(boolean inProgress)
	{
		_removeCupInProgress = inProgress;
	}
	
	public void storeVariablesForSaveState(Bundle savedInstanceState)
	{
		savedInstanceState.putString("_teamOneRecord", _teamOneRecord);
		savedInstanceState.putString("_teamTwoRecord", _teamTwoRecord);
		
		savedInstanceState.putStringArrayList("_playerOneCurrentStats", _currentStats.get(0));
		savedInstanceState.putStringArrayList("_playerTwoCurrentStats", _currentStats.get(1));
		savedInstanceState.putStringArrayList("_playerThreeCurrentStats", _currentStats.get(2));
		savedInstanceState.putStringArrayList("_playerFourCurrentStats", _currentStats.get(3));
		
		savedInstanceState.putInt("_teamOneCupsRemaining", _teamOneCupsRemaining);
		savedInstanceState.putInt("_teamTwoCupsRemaining", _teamTwoCupsRemaining);
		
		savedInstanceState.putInt("_overtimeCount", _overtimeCount);
		savedInstanceState.putInt("_requestCount", _requestCount);
		
		savedInstanceState.putStringArrayList("_rules", _rules);
		
		savedInstanceState.putBoolean("_shotInProgress", _shotInProgress);
		savedInstanceState.putBoolean("_shouldEnterOvertime", _shouldEnterOvertime);
		
		savedInstanceState.putInt("_cupsLeftToRemove", _cupsLeftToRemove);
		savedInstanceState.putInt("_currentGangBangAmount", _currentGangBangAmount);
		
		savedInstanceState.putString("_shotHistoryString", _shotHistoryString);
		
		savedInstanceState.putBoolean("_statsUpdatedSuccessfully", _statsUpdatedSuccessfully);
		savedInstanceState.putInt("_statsUpdatedCount", _statsUpdatedCount);
		
		int id = 0;
		
		for (Button cup : _teamOneCups)
		{
			if (cup.getVisibility() == View.INVISIBLE)
			{
				savedInstanceState.putBoolean("_teamOneCups" + id, true);
			}
			else
			{
				savedInstanceState.putBoolean("_teamOneCups" + id, false);
			}
			
			id++;
		}
		
		id = 0;
		
		for (Button cup : _teamTwoCups)
		{
			if (cup.getVisibility() == View.INVISIBLE)
			{
				savedInstanceState.putBoolean("_teamTwoCups" + id, true);
			}
			else
			{
				savedInstanceState.putBoolean("_teamTwoCups" + id, false);
			}
			
			id++;
		}
		
		for (PongPlayer player : _players)
		{
			player.storeVariablesForSaveState(savedInstanceState);
		}
	}
	
	public void reloadVariablesFromSaveState(Bundle savedInstanceState)
	{
		_teamOneRecord = savedInstanceState.getString("_teamOneRecord");
		_teamTwoRecord = savedInstanceState.getString("_teamTwoRecord");
		
		_currentStats.set(0, savedInstanceState.getStringArrayList("_playerOneCurrentStats"));
		_currentStats.set(1, savedInstanceState.getStringArrayList("_playerTwoCurrentStats"));
		_currentStats.set(2, savedInstanceState.getStringArrayList("_playerThreeCurrentStats"));
		_currentStats.set(3, savedInstanceState.getStringArrayList("_playerFourCurrentStats"));
		
		_teamOneCupsRemaining = savedInstanceState.getInt("_teamOneCupsRemaining");
		_teamTwoCupsRemaining = savedInstanceState.getInt("_teamTwoCupsRemaining");
		
		_overtimeCount = savedInstanceState.getInt("_overtimeCount");
		_requestCount = savedInstanceState.getInt("_requestCount");
		
		_rules = savedInstanceState.getStringArrayList("_rules");
		
		_shotInProgress = savedInstanceState.getBoolean("_shotInProgress");
		_shouldEnterOvertime = savedInstanceState.getBoolean("_shouldEnterOvertime");
		
		_cupsLeftToRemove = savedInstanceState.getInt("_cupsLeftToRemove");
		_currentGangBangAmount = savedInstanceState.getInt("_currentGangBangAmount");
		
		_shotHistoryString = savedInstanceState.getString("_shotHistoryString");
		
		_statsUpdatedSuccessfully = savedInstanceState.getBoolean("_statsUpdatedSuccessfully");
		_statsUpdatedCount = savedInstanceState.getInt("_statsUpdatedCount");
		
		int id = 0;
		
		for (Button cup : _teamOneCups)
		{
			if (savedInstanceState.getBoolean("_teamOneCups" + id) == true)
			{
				cup.setVisibility(View.INVISIBLE);
			}
			
			rerackCups(ID_TEAM_ONE);
			
			id++;
		}
		
		id = 0;
		
		for (Button cup : _teamTwoCups)
		{
			if (savedInstanceState.getBoolean("_teamTwoCups" + id) == true)
			{
				cup.setVisibility(View.INVISIBLE);
			}
			
			rerackCups(ID_TEAM_TWO);
			
			id++;
		}
		
		for (PongPlayer player : _players)
		{
			player.reloadVariablesFromSaveState(savedInstanceState);
		}
	}
}
