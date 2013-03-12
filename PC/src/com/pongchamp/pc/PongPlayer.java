package com.pongchamp.pc;

import java.net.URL;
import java.util.ArrayList;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;

import android.annotation.SuppressLint;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.media.MediaPlayer;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.StrictMode;
import android.text.InputType;
import android.view.View;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ListView;

@SuppressLint("NewApi")
public class PongPlayer
{
	PCUser _activeUser = null;
	
	PongPlayerDisplay _display = null;
	String _name = null;
	Game _gameRef = null;
	Context _context = null;
	int _teamID = 0;
	int _activeShotType = 0;
	boolean _isActiveShooter = false;
	boolean _hasEnteredOvertime = false;
	boolean _statsAlreadyUploaded = false;
	
	int _playerID = 0;
	
	ArrayList<String> _rules = null;
	
	ListView _shotTypes = null;
	AlertDialog.Builder _selectShot = null;
	AlertDialog _dialogRef = null;
	
	ArrayList<Float> _stats = null;
	
	int _opponentCupsRemaining = 0;
	int _ownCupsRemaining = 0;
	
	float _eloRating = 0;
	int _cupDifferential = 0;
	int _gamesPlayed = 0;
	int _rank = 0;
	
	final double K_FACTOR = 20;
	final double RATE_OF_CHANGE = 400;
	
	MediaPlayer _heatingUpSound = null;
	MediaPlayer _onFireSound = null;
	
	ArrayList<NameValuePair> _eloRatings = null;
	
	final int ID_HIT = 0;
	final int ID_MISS = 1;
	final int ID_BOUNCE = 2;
	final int ID_GANG_BANG = 3;
	final int ID_ERROR = 4;
		
	final int ID_STARTING_CUPS = 0;
	final int ID_BOUNCES_WORTH = 1;
	final int ID_BOUNCE_IN_REDEMP = 2;
	final int ID_NBA_JAM = 3;
	
	final String _left = "left";
	final String _right = "right";
	
	final String _heatingUp = " (HU)";
	final String _onFire = " (OF)";
	
	/* The following variables are associated with the player's stats. */
	int stat_currentHitStreak = 0;
	int stat_currentMissStreak = 0;
	int stat_highestHitStreak = 0;
	int stat_highestMissStreak = 0;
	int stat_shotsTaken = 0;
	int stat_shotsHit = 0;
	int stat_bouncesHit = 0;
	int stat_gangBangsHit = 0;
	int stat_errorsCommitted = 0;
	int stat_heatingUp = 0;
	int stat_onFire = 0;
	int stat_redemptionShotsTaken = 0;
	int stat_redemptionShotsHit = 0;
	int stat_redemptionAttempts = 0;
	int stat_redemptionSuccesses = 0;
	ArrayList<Integer> stat_shotsPerCup = null;
	ArrayList<Integer> stat_hitsPerCup = null;
	
	boolean stat_redemptionInProgress = false;
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	
	/* All Stat ID's */
	final int ID_WINS = 0;
	final int ID_LOSSES = 1;
	final int ID_OT_LOSSES = 2;
	final int ID_CUP_DIF = 3;
	final int ID_WIN_STREAK = 4;
	final int ID_LOSS_STREAK = 5;
	final int ID_SHOTS = 6;
	final int ID_HITS = 7;
	final int ID_HIT_STREAK = 8;
	final int ID_MISS_STREAK = 9;
	final int ID_BOUNCES = 10;
	final int ID_GANG_BANGS = 11;
	final int ID_ERRORS = 12;
	final int ID_REDEMP_SHOTS = 13;
	final int ID_REDEMP_HITS = 14;
	final int ID_REDEMP_ATMPS = 15;
	final int ID_REDEMP_SUCCS = 16;
	final int ID_ELO_RATING = 17;
	final int ID_S10 = 18;
	final int ID_H10 = 19;
	final int ID_S9 = 20;
	final int ID_H9 = 21;
	final int ID_S8 = 22;
	final int ID_H8 = 23;
	final int ID_S7 = 24;
	final int ID_H7 = 25;
	final int ID_S6 = 26;
	final int ID_H6 = 27;
	final int ID_S5 = 28;
	final int ID_H5 = 29;
	final int ID_S4 = 30;
	final int ID_H4 = 31;
	final int ID_S3 = 32;
	final int ID_H3 = 33;
	final int ID_S2 = 34;
	final int ID_H2 = 35;
	final int ID_S1 = 36;
	final int ID_H1 = 37;
	final int ID_CUR_WIN_STREAK = 38;
	final int ID_CUR_LOSS_STREAK = 39;
	final int ID_HEATING_UP = 40;
	final int ID_ON_FIRE = 41;
	/* * * * * * * * */
	
	String _getPlayerURL = "http://www.pongchamp.com/getplayer.php";
	String _updateStatsURL = "http://www.pongchamp.com/updatestats.php";
	
	public PongPlayer(PongPlayerDisplay display, int id, Game gameRef, Context context, PCUser pcUser, int playerID, MediaPlayer heatingUpSound, MediaPlayer onFireSound)
	{
		StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
        StrictMode.setThreadPolicy(policy);
        
        _activeUser = pcUser;
		_display = display;
		_teamID = id;
		_gameRef = gameRef;
		_context = context;
		
		_heatingUpSound = heatingUpSound;
		_onFireSound = onFireSound;
		
		_playerID = playerID;
		
		_name = getName();
				
		_rules = _gameRef.getRules();
				
		onDisplayButtonPressed();
		initializeStatsArray();
				
		stat_shotsPerCup = new ArrayList<Integer>(10);
		stat_hitsPerCup = new ArrayList<Integer>(10);
		
		for (int i = 0; i < 10; i++)
		{
			stat_shotsPerCup.add(0);
			stat_hitsPerCup.add(0);
		}
	}
	
	public void onDisplayButtonPressed()
	{	
		_display.getButton().setOnClickListener(new View.OnClickListener()
		{	
			/* Pressing this button makes the player the active shooter.
			 * 
			 * A dialog window is presented, show the user the shot types
			 * for the current shooter.
			 * 
			 * Appropriate stats, streaks, etc., are housed in this class.
			 */
			@SuppressLint("NewApi")
			public void onClick(View v)
			{
				setButtonPressed(_display.getButton(), true);
				
				_gameRef.playerIconPressed(_display.getButton());
				
				_selectShot = new AlertDialog.Builder(v.getContext());
				_shotTypes = new ListView(v.getContext());

				ArrayAdapter<String> adapter = new ArrayAdapter<String>(v.getContext(),
						android.R.layout.simple_list_item_1);

				if (_opponentCupsRemaining > 0)
				{
					adapter.add(_context.getString(R.string.shot_type_hit));
					adapter.add(_context.getString(R.string.shot_type_miss));
					adapter.add(_context.getString(R.string.shot_type_bounce));
					adapter.add(_context.getString(R.string.shot_type_gang_bang));
				}
				
				adapter.add(_context.getString(R.string.shot_type_error));
				
				_shotTypes.setAdapter(adapter);

				shotTypeClicked();

				_selectShot.setView(_shotTypes);

				_dialogRef = _selectShot.show();
			}
		});
	}
	
	private void initializeStatsArray()
	{
		_stats = new ArrayList<Float>();
	}
	
	public int getShots()
	{
		return stat_shotsTaken;
	}
	
	public int getHits()
	{
		return stat_shotsHit;
	}
	
	public int getCurrentHitStreak(boolean isNBAJam)
	{
		if (isNBAJam)
		{
			if (nbaJamRule())
			{
				return stat_currentHitStreak;
			}
			else
			{
				return 0;
			}
		}
		
		return stat_currentHitStreak;
	}
	
	public void setButtonPressed(Button btn, boolean pressed)
	{
		if (pressed)
		{
			btn.setBackgroundResource(R.drawable.player_icon_down);
			btn.setEnabled(false);
			
			_isActiveShooter = true;
		}
		else
		{
			btn.setBackgroundResource(R.drawable.player_icon);
			btn.setEnabled(true);
			
			_isActiveShooter = false;
		}
	}
	
	public PongPlayerDisplay getDisplay()
	{
		return _display;
	}
	
	public boolean isActiveShooter()
	{
		return _isActiveShooter;
	}
	
	public int getTeamID()
	{
		return _teamID;
	}
	
	public int getActiveShotType()
	{
		return _activeShotType;
	}
	
	private void shotTypeClicked()
	{
		_shotTypes.setOnItemClickListener(new OnItemClickListener()
		{
			public void onItemClick(AdapterView<?> adapter, View v, int pos,
					long id)
			{
				/* Only allow 'Error' to be pressed when opponent
				 * has no cups remaining. */
				if (_opponentCupsRemaining == 0)
				{
					pos += 4;
				}
				
				switch (pos)
				{
					case ID_HIT:
					{
						 _activeShotType = ID_HIT;
					}
						break;
							
					case ID_MISS:
					{
						_activeShotType = ID_MISS;
						
						_gameRef.clearActiveShooters();
						
						miss();
						
						_gameRef.updateStatsTab();
					}
						break;
						
					case ID_BOUNCE:
					{
						_activeShotType = ID_BOUNCE;
					}
						break;
						
					case ID_GANG_BANG:
					{
						_activeShotType = ID_GANG_BANG;
						
						final AlertDialog.Builder cupsToRemove = new AlertDialog.Builder(v.getContext());
						final EditText editText = new EditText(v.getContext());
						
						editText.setInputType(InputType.TYPE_CLASS_NUMBER);
						
						cupsToRemove.setTitle(_context.getString(R.string.gang_bang_title));
						cupsToRemove.setView(editText);
						
						/* Fail-safe in case user presses off popup. */
						_gameRef.setCurrentGangBangAmount(1, _teamID);
						
						cupsToRemove.setPositiveButton("Okay", new DialogInterface.OnClickListener()
						{	
							public void onClick(DialogInterface dialog, int which)
							{
								if (editText.getText().toString().equals(""))
								{
									_gameRef.setCurrentGangBangAmount(1, _teamID);
								}
								else
								{
									_gameRef.setCurrentGangBangAmount(Integer.parseInt(editText.getText().toString()), _teamID);
								}
							}
						});
						
						cupsToRemove.show();
					}
						break;
						
					case ID_ERROR:
					{
						_activeShotType = ID_ERROR;
					}
						break;
						
					default:
						break;
				}
				
				_dialogRef.dismiss(); 
			}
		});
	}
	
	public void setCupsRemaining(int opponentCupsRemaining, int ownCupsRemaining)
	{
		_opponentCupsRemaining = opponentCupsRemaining;
		_ownCupsRemaining = ownCupsRemaining;
	}
	
	public void hit(boolean addToShotHistory)
	{
		if (addToShotHistory)
		{
			addStringToShotHistory(ID_HIT);
		}
		
		stat_shotsTaken++;
		stat_shotsHit++;
		stat_currentHitStreak++;
		
		if (nbaJamRule())
		{
			if (stat_currentHitStreak == 2)
			{
				stat_heatingUp++;
				
				_heatingUpSound.start();
			}
			else if (stat_currentHitStreak == 3)
			{
				stat_onFire++;
				
				_onFireSound.start();
			}
		}		

		updateHighestStreaks();
		addShotForCup(_opponentCupsRemaining - 1);
		addHitForCup(_opponentCupsRemaining - 1);

		stat_currentMissStreak = 0;
		
		if (isRedemption())
		{
			stat_redemptionShotsTaken++;
			stat_redemptionShotsHit++;
			
			if (!stat_redemptionInProgress)
			{
				stat_redemptionInProgress = true;
				
				stat_redemptionAttempts++;
			}

			if (_opponentCupsRemaining == 1)
			{
				stat_redemptionSuccesses++;
				
				stat_redemptionInProgress = false;
			}
		}
		
		setNBAJamState();
	}
	
	public void miss()
	{
		addStringToShotHistory(ID_MISS);
		
		stat_shotsTaken++;
		stat_currentMissStreak++;
		
		updateHighestStreaks();
		addShotForCup(_opponentCupsRemaining - 1);
		
		stat_currentHitStreak = 0;
		
		if (isRedemption())
		{
			stat_redemptionShotsTaken++;
			
			if (!stat_redemptionInProgress)
			{
				stat_redemptionAttempts++;
			}
			else
			{
				stat_redemptionInProgress = false;
			}
			
			_display.getButton().setEnabled(false);
		}
		
		setNBAJamState();
	}
	
	public void bounce()
	{
		addStringToShotHistory(ID_BOUNCE);
		
		stat_shotsTaken++;
		stat_shotsHit += bouncesWorth();
		stat_currentHitStreak++;
		stat_bouncesHit++;
		
		if (nbaJamRule())
		{
			if (stat_currentHitStreak == 2)
			{
				stat_heatingUp++;
				
				_heatingUpSound.start();
			}
			else if (stat_currentHitStreak == 3)
			{
				stat_onFire++;
				
				_onFireSound.start();
			}
		}		

		updateHighestStreaks();
		addShotForCup(_opponentCupsRemaining - 1);
		addHitForCup(_opponentCupsRemaining - 1);

		stat_currentMissStreak = 0;
		
		if (isRedemption())
		{
			stat_redemptionShotsTaken++;
			stat_redemptionShotsHit++;
			
			if (!stat_redemptionInProgress)
			{
				stat_redemptionInProgress = true;
				
				stat_redemptionAttempts++;
			}

			if (_opponentCupsRemaining <= 2 || bounceInRedemptionToOvertime())
			{
				stat_redemptionSuccesses++;
				
				stat_redemptionInProgress = false;
				
				_gameRef.bounceInRedemption();
			}
		}
		
		setNBAJamState();
	}
	
	public void gangBang()
	{
		addStringToShotHistory(ID_GANG_BANG);
		
		stat_gangBangsHit++;
		
		hit(false);
	}
	
	public void error()
	{
		addStringToShotHistory(ID_ERROR);
		
		stat_errorsCommitted++;
	}
	
	private boolean isRedemption()
	{
		if (_ownCupsRemaining == 0)
		{
			return true;
		}
		
		return false;
	}
	
	private void updateHighestStreaks()
	{
		if (stat_currentHitStreak > stat_highestHitStreak)
		{
			stat_highestHitStreak = stat_currentHitStreak;
		}
		
		if (stat_currentMissStreak > stat_highestMissStreak)
		{
			stat_highestMissStreak = stat_currentMissStreak;
		}
	}
	
	private void addShotForCup(int cup)
	{
		int count = stat_shotsPerCup.get(cup);
		
		count++;
		
		stat_shotsPerCup.set(cup, count);
	}
	
	private void addHitForCup(int cup)
	{
		int count = stat_hitsPerCup.get(cup);
		
		count++;
		
		stat_hitsPerCup.set(cup, count);
	}
	
	private boolean nbaJamRule()
	{
		String rule = _rules.get(ID_NBA_JAM);
		
		if (rule.equals(_left))
		{
			return true;
		}
		
		return false;
	}
	
	private int bouncesWorth()
	{
		String rule = _rules.get(ID_BOUNCES_WORTH);
		
		if (rule.equals(_left) || _opponentCupsRemaining == 1)
		{
			return 1;
		}
		
		return 2;
	}
	
	private boolean bounceInRedemptionToOvertime()
	{
		String rule = _rules.get(ID_BOUNCE_IN_REDEMP);
		
		if (rule.equals(_left))
		{
			return true;
		}
		
		return false;
	}
	
	public ArrayList<Float> getStats()
	{
		return _stats;
	}
	
	public void setStats(ArrayList<Float> stats)
	{
		_stats = stats;
	}
	
	public boolean getHasEnteredOvertime()
	{
		return _hasEnteredOvertime;
	}
	
	public void setHasEnteredOvertime(boolean overtime)
	{
		_hasEnteredOvertime = overtime;
	}
	
	public void processFinalStats()
	{
		fillStats();
	}
	
	private void updateStatsDatabase()
	{
		new PerformBackgroundTaskSaveStats().execute();
	}
	
	private void checkStatsUpdateResult(String result)
	{
		if (result != null && result.equalsIgnoreCase("1"))
		{
			_gameRef.updateStatsUpdatedCount();
			
			_statsAlreadyUploaded = true;
		}
	}
	
	private class PerformBackgroundTaskSaveStats extends AsyncTask<URL, Integer, String>
	{
		@Override
		protected String doInBackground(URL... params)
		{
			String result = null;
			
			if (_statsAlreadyUploaded == false)
			{
				HTTPHelper httpHelper = new HTTPHelper();
				ArrayList<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(42);
				String name = _display.getTextView().getText().toString();
				int count = 0;
				
				nameValuePairs.add(new BasicNameValuePair("username", _activeUser.getUsername()));
				nameValuePairs.add(new BasicNameValuePair("name", name));
				
				for (Float value : _stats)
				{
					nameValuePairs.add(new BasicNameValuePair(Integer.toString(count), Float.toString(value)));
					
					count++;
				}
				
				if (_gameRef.getOvertimeCount() > 0)
				{
					nameValuePairs.add(new BasicNameValuePair("ot_count", Integer.toString(_gameRef.getOvertimeCount())));
				}
				else
				{
					nameValuePairs.add(new BasicNameValuePair("ot_count", "0"));
				}
				
				result = httpHelper.executeHttpPost(nameValuePairs, _updateStatsURL);
			}
			else
			{
				result = "1";
			}
			
			return result;
		}
		
		protected void onPostExecute(String result)
		{
			if (result != null)
			{
				checkStatsUpdateResult(result);

				_gameRef.incrementRequestCount();
				
				if (_gameRef.getRequestCount() == 4)
				{
					_gameRef.setRequestCount(0);
					
					_gameRef.saveGameResult();
				}
			}
			else
			{
				new Utilities().showNoInternetConnectionAlert(_context);
			}
		}
	}
	
	private void fillStats()
	{
		_display.getTextView().setText(_name);
		
		new PerformBackgroundTaskGetStats().execute();
	}
	
	private class PerformBackgroundTaskGetStats extends AsyncTask<URL, Integer, String>
	{
		@Override
		protected String doInBackground(URL... params)
		{
			HTTPHelper httpHelper = new HTTPHelper();
			ArrayList<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);
			
			nameValuePairs.add(new BasicNameValuePair("username", _activeUser.getUsername()));
			nameValuePairs.add(new BasicNameValuePair("name", replaceSpacesWithEntities(_name)));
			
			String result = httpHelper.executeHttpGet(nameValuePairs, _getPlayerURL);
			
			return result;
		}
		
		protected void onPostExecute(String result)
		{
			if (result != null)
			{
				ArrayList<Float> tempStats = new ArrayList<Float>();
				
				float val;
				
				for (int i = 0; i < 42; i++)
				{
					tempStats.add((float) 0);
				}
				
				ArrayList<NameValuePair> player = new ArrayList<NameValuePair>();
				CustomJSONParser JSONParser = new CustomJSONParser();
				
				player = JSONParser.parseJSONString(result);
				
				addStatsToArray(player);
				
				/* The game outcome (win, loss, ot loss). */
				if (_ownCupsRemaining > 0)
				{
					tempStats.set(ID_WINS, (float) 1);
					
					if (!_hasEnteredOvertime)
					{
						tempStats.set(ID_CUP_DIF, (float) _ownCupsRemaining);
					}
					
					tempStats.set(ID_CUR_WIN_STREAK, (float) 1);
				}
				else
				{
					if (_hasEnteredOvertime)
					{
						tempStats.set(ID_OT_LOSSES, (float) 1);
					}
					else
					{
						tempStats.set(ID_LOSSES, (float) 1);
						tempStats.set(ID_CUP_DIF, (float) (_opponentCupsRemaining * -1));
					}
					
					tempStats.set(ID_CUR_LOSS_STREAK, (float) 1);
				}
				/******************************************/
				
				/* The shots. */
				tempStats.set(ID_SHOTS, (float) stat_shotsTaken);
				tempStats.set(ID_HITS, (float) stat_shotsHit);
				tempStats.set(ID_HIT_STREAK, (float) stat_highestHitStreak);
				tempStats.set(ID_MISS_STREAK, (float) stat_highestMissStreak);
				tempStats.set(ID_BOUNCES, (float) stat_bouncesHit);
				tempStats.set(ID_GANG_BANGS, (float) stat_gangBangsHit);
				tempStats.set(ID_ERRORS, (float) stat_errorsCommitted);
				tempStats.set(ID_REDEMP_SHOTS, (float) stat_redemptionShotsTaken);
				tempStats.set(ID_REDEMP_HITS, (float) stat_redemptionShotsHit);
				tempStats.set(ID_REDEMP_ATMPS, (float) stat_redemptionAttempts);
				tempStats.set(ID_REDEMP_SUCCS, (float) stat_redemptionSuccesses);
				tempStats.set(ID_HEATING_UP, (float) stat_heatingUp);
				tempStats.set(ID_ON_FIRE, (float) stat_onFire);
				
				tempStats.set(ID_S10, (float) stat_shotsPerCup.get(9));
				tempStats.set(ID_S9, (float) stat_shotsPerCup.get(8));
				tempStats.set(ID_S8, (float) stat_shotsPerCup.get(7));
				tempStats.set(ID_S7, (float) stat_shotsPerCup.get(6));
				tempStats.set(ID_S6, (float) stat_shotsPerCup.get(5));
				tempStats.set(ID_S5, (float) stat_shotsPerCup.get(4));
				tempStats.set(ID_S4, (float) stat_shotsPerCup.get(3));
				tempStats.set(ID_S3, (float) stat_shotsPerCup.get(2));
				tempStats.set(ID_S2, (float) stat_shotsPerCup.get(1));
				tempStats.set(ID_S1, (float) stat_shotsPerCup.get(0));
				
				tempStats.set(ID_H10, (float) stat_hitsPerCup.get(9));
				tempStats.set(ID_H9, (float) stat_hitsPerCup.get(8));
				tempStats.set(ID_H8, (float) stat_hitsPerCup.get(7));
				tempStats.set(ID_H7, (float) stat_hitsPerCup.get(6));
				tempStats.set(ID_H6, (float) stat_hitsPerCup.get(5));
				tempStats.set(ID_H5, (float) stat_hitsPerCup.get(4));
				tempStats.set(ID_H4, (float) stat_hitsPerCup.get(3));
				tempStats.set(ID_H3, (float) stat_hitsPerCup.get(2));
				tempStats.set(ID_H2, (float) stat_hitsPerCup.get(1));
				tempStats.set(ID_H1, (float) stat_hitsPerCup.get(0));
				/**************/
				
				double ownRating = 0;
				double opponentRating = 0;
				boolean isOwnRatingHigher = false;
				
				for (NameValuePair nameValuePair : _eloRatings)
				{
					if (Integer.parseInt(nameValuePair.getName()) == _teamID)
					{
						ownRating += Double.parseDouble(nameValuePair.getValue());
					}
					else
					{
						opponentRating += Double.parseDouble(nameValuePair.getValue());
					}
				}
				
				isOwnRatingHigher = (ownRating > opponentRating);
				
				double expectedValueA = (1.0) / (1.0 + (Math.pow(10.0, ((ownRating - opponentRating) / RATE_OF_CHANGE))));
				double expectedValueB = (1.0 - expectedValueA);
				
				if (tempStats.get(ID_WINS) == (float) 1)
				{
					if (isOwnRatingHigher)
					{
						tempStats.set(ID_ELO_RATING, (float) (K_FACTOR * Math.min(expectedValueA, expectedValueB)));
					}
					else
					{
						tempStats.set(ID_ELO_RATING, (float) (K_FACTOR * Math.max(expectedValueA, expectedValueB)));
					}
				}
				else
				{
					if (isOwnRatingHigher)
					{
						tempStats.set(ID_ELO_RATING, (float) ((-1.0) * K_FACTOR * Math.max(expectedValueA, expectedValueB)));
					}
					else
					{
						tempStats.set(ID_ELO_RATING, (float) ((-1.0) * K_FACTOR * Math.min(expectedValueA, expectedValueB)));
					}
				}
				
				if (tempStats.get(ID_CUR_WIN_STREAK) > 0)
				{
					val = _stats.get(ID_CUR_WIN_STREAK);
					
					val++;
					
					_stats.set(ID_CUR_WIN_STREAK, val);
					_stats.set(ID_CUR_LOSS_STREAK, (float) 0);
					
					if (val > _stats.get(ID_WIN_STREAK))
					{
						_stats.set(ID_WIN_STREAK, val);
					}
				}
				else
				{
					val = _stats.get(ID_CUR_LOSS_STREAK);
					
					val++;
					
					_stats.set(ID_CUR_LOSS_STREAK, val);
					_stats.set(ID_CUR_WIN_STREAK, (float) 0);
					
					if (val > _stats.get(ID_LOSS_STREAK))
					{
						_stats.set(ID_LOSS_STREAK, val);
					}
				}
				
				if (tempStats.get(ID_HIT_STREAK) > _stats.get(ID_HIT_STREAK))
				{
					_stats.set(ID_HIT_STREAK, tempStats.get(ID_HIT_STREAK));
				}
				
				if (tempStats.get(ID_MISS_STREAK) > _stats.get(ID_MISS_STREAK))
				{
					_stats.set(ID_MISS_STREAK, tempStats.get(ID_MISS_STREAK));
				}
				
				for (int i = 0; i < 42; i++)
				{
					if ((i != ID_WIN_STREAK) &&
						(i != ID_LOSS_STREAK) &&
						(i != ID_HIT_STREAK) &&
						(i != ID_MISS_STREAK) &&
						(i != ID_CUR_WIN_STREAK) &&
						(i != ID_CUR_LOSS_STREAK))
					{
						val = _stats.get(i);
						
						val += tempStats.get(i);
						
						_stats.set(i, val);
						
						if (i == ID_ELO_RATING)
						{
							if (_stats.get(i) < 0)
							{
								_stats.set(i, (float) 0);
							}
						}
					}
				}
				
				updateStatsDatabase();
			}
			else
			{
				new Utilities().showNoInternetConnectionAlert(_context);
			}
		}
	}
	
	private String replaceSpacesWithEntities(String str)
	{
		return str.replace(" ", "%20");
	}
	
	private void addStatsToArray(ArrayList<NameValuePair> nameValuePairs)
	{
		int count = 0;
		
		for (NameValuePair nameValuePair : nameValuePairs)
		{
			if (count >= 3)
			{
				_stats.add(Float.parseFloat(nameValuePair.getValue()));
			}
			
			count++;
		}
	}
	
	public void addStringToShotHistory(int id)
	{
		String str = null;
		String cupStr = null;
		
		if (_opponentCupsRemaining == 1)
		{
			cupStr = _context.getString(R.string.shot_history_cup);
		}
		else
		{
			cupStr = _context.getString(R.string.shot_history_cups);
		}
		
		switch (id)
		{
			case ID_HIT:
			{
				str = getName() + " " + _context.getString(R.string.shot_history_hit) + " " + _opponentCupsRemaining + " " + cupStr;
			}
				break;
				
			case ID_MISS:
			{
				str = getName() + " " + _context.getString(R.string.shot_history_miss) + " " + _opponentCupsRemaining + " " + cupStr;
			}
				break;
				
			case ID_BOUNCE:
			{
				str = getName() + " " + _context.getString(R.string.shot_history_bounce) + " " + _opponentCupsRemaining + " " + cupStr;
			}
				break;
				
			case ID_GANG_BANG:
			{
				str = getName() + " " + _context.getString(R.string.shot_history_gangbang) + " " + _opponentCupsRemaining + " " + cupStr;
			}
				break;
				
			case ID_ERROR:
			{
				str = getName() + " " + _context.getString(R.string.shot_history_error) + " " + _opponentCupsRemaining + " " + cupStr;
			}
				break;
				
			default:
				break;
		}
		
		str += "\n--------------------------------------------------\n";
		
		_gameRef.concatenateShotHistory(str);
	}
	
	private void setNBAJamState()
	{
		if (nbaJamRule())
		{
			int count = stat_currentHitStreak;
			
			if (count < 2)
			{
				_display.getTextView().setText(_name);
			}
			else if (count == 2)
			{
				_display.getTextView().setText(_name + _heatingUp);
			}
			else if (count > 2)
			{
				_display.getTextView().setText(_name + _onFire);
			}
		}
	}
	
	public void setEloRating(float rating)
	{
		_eloRating = rating;
	}
	
	public void setCupDifferential(int diff)
	{
		_cupDifferential = diff;
	}
	
	public void setEloRatings(ArrayList<NameValuePair> ratings)
	{
		_eloRatings = ratings;
	}
	
	public void setGamesPlayed(int gamesPlayed)
	{
		_gamesPlayed = gamesPlayed;
	}
	
	public void setRank(int rank)
	{
		_rank = rank;
	}
	
	public int getCupDifferential()
	{
		return _cupDifferential;
	}
	
	public int getGamesPlayed()
	{
		return _gamesPlayed;
	}
	
	public String getName()
	{
		return _display.getTextView().getText().toString();
	}
	
	public int getRank()
	{
		return _rank;
	}
	
	public void storeVariablesForSaveState(Bundle savedInstanceState)
	{
		switch (_playerID)
		{
			case 0:
			{
				savedInstanceState.putString("_name0", _name);
				savedInstanceState.putInt("_teamID0", _teamID);
				savedInstanceState.putInt("_activeShotType0", _activeShotType);
				savedInstanceState.putBoolean("_isActiveShooter0", _isActiveShooter);
				savedInstanceState.putBoolean("_hasEnteredOvertime0", _hasEnteredOvertime);
				savedInstanceState.putBoolean("_statsAlreadyUploaded0", _statsAlreadyUploaded);
				
				savedInstanceState.putStringArrayList("_rulesPlayer0", _rules);
				
				savedInstanceState.putInt("_opponentCupsRemaining0", _opponentCupsRemaining);
				savedInstanceState.putInt("_ownCupsRemaining0", _ownCupsRemaining);
				
				savedInstanceState.putFloat("_eloRating0", _eloRating);
				savedInstanceState.putInt("_cupDifferential0", _cupDifferential);
				savedInstanceState.putInt("_gamesPlayed0", _gamesPlayed);
				savedInstanceState.putInt("_rank0", _rank);
				
				savedInstanceState.putInt("stat_currentHitStreak0", stat_currentHitStreak);
				savedInstanceState.putInt("stat_currentMissStreak0", stat_currentMissStreak);
				savedInstanceState.putInt("stat_highestHitStreak0", stat_highestHitStreak);
				savedInstanceState.putInt("stat_highestMissStreak0", stat_highestMissStreak);
				savedInstanceState.putInt("stat_shotsTaken0", stat_shotsTaken);
				savedInstanceState.putInt("stat_shotsHit0", stat_shotsHit);
				savedInstanceState.putInt("stat_bouncesHit0", stat_bouncesHit);
				savedInstanceState.putInt("stat_gangBangsHit0", stat_gangBangsHit);
				savedInstanceState.putInt("stat_errorsCommitted0", stat_errorsCommitted);
				savedInstanceState.putInt("stat_heatingUp0", stat_heatingUp);
				savedInstanceState.putInt("stat_onFire0", stat_onFire);
				savedInstanceState.putInt("stat_redemptionShotsTaken0", stat_redemptionShotsTaken);
				savedInstanceState.putInt("stat_redemptionShotsHit0", stat_redemptionShotsHit);
				savedInstanceState.putInt("stat_redemptionAttempts0", stat_redemptionAttempts);
				savedInstanceState.putInt("stat_redemptionSuccesses0", stat_redemptionSuccesses);
				
				savedInstanceState.putIntegerArrayList("stat_shotsPerCup0", stat_shotsPerCup);
				savedInstanceState.putIntegerArrayList("stat_hitsPerCup0", stat_hitsPerCup);
				
				savedInstanceState.putBoolean("stat_redemptionInProgress0", stat_redemptionInProgress);
			}
				break;
				
			case 1:
			{
				savedInstanceState.putString("_name1", _name);
				savedInstanceState.putInt("_teamID1", _teamID);
				savedInstanceState.putInt("_activeShotType1", _activeShotType);
				savedInstanceState.putBoolean("_isActiveShooter1", _isActiveShooter);
				savedInstanceState.putBoolean("_hasEnteredOvertime1", _hasEnteredOvertime);
				savedInstanceState.putBoolean("_statsAlreadyUploaded1", _statsAlreadyUploaded);
				
				savedInstanceState.putStringArrayList("_rulesPlayer1", _rules);
				
				savedInstanceState.putInt("_opponentCupsRemaining1", _opponentCupsRemaining);
				savedInstanceState.putInt("_ownCupsRemaining1", _ownCupsRemaining);
				
				savedInstanceState.putFloat("_eloRating1", _eloRating);
				savedInstanceState.putInt("_cupDifferential1", _cupDifferential);
				savedInstanceState.putInt("_gamesPlayed1", _gamesPlayed);
				savedInstanceState.putInt("_rank1", _rank);
				
				savedInstanceState.putInt("stat_currentHitStreak1", stat_currentHitStreak);
				savedInstanceState.putInt("stat_currentMissStreak1", stat_currentMissStreak);
				savedInstanceState.putInt("stat_highestHitStreak1", stat_highestHitStreak);
				savedInstanceState.putInt("stat_highestMissStreak1", stat_highestMissStreak);
				savedInstanceState.putInt("stat_shotsTaken1", stat_shotsTaken);
				savedInstanceState.putInt("stat_shotsHit1", stat_shotsHit);
				savedInstanceState.putInt("stat_bouncesHit1", stat_bouncesHit);
				savedInstanceState.putInt("stat_gangBangsHit1", stat_gangBangsHit);
				savedInstanceState.putInt("stat_errorsCommitted1", stat_errorsCommitted);
				savedInstanceState.putInt("stat_heatingUp1", stat_heatingUp);
				savedInstanceState.putInt("stat_onFire1", stat_onFire);
				savedInstanceState.putInt("stat_redemptionShotsTaken1", stat_redemptionShotsTaken);
				savedInstanceState.putInt("stat_redemptionShotsHit1", stat_redemptionShotsHit);
				savedInstanceState.putInt("stat_redemptionAttempts1", stat_redemptionAttempts);
				savedInstanceState.putInt("stat_redemptionSuccesses1", stat_redemptionSuccesses);
				
				savedInstanceState.putIntegerArrayList("stat_shotsPerCup1", stat_shotsPerCup);
				savedInstanceState.putIntegerArrayList("stat_hitsPerCup1", stat_hitsPerCup);
				
				savedInstanceState.putBoolean("stat_redemptionInProgress1", stat_redemptionInProgress);
			}
				break;
				
			case 2:
			{
				savedInstanceState.putString("_name2", _name);
				savedInstanceState.putInt("_teamID2", _teamID);
				savedInstanceState.putInt("_activeShotType2", _activeShotType);
				savedInstanceState.putBoolean("_isActiveShooter2", _isActiveShooter);
				savedInstanceState.putBoolean("_hasEnteredOvertime2", _hasEnteredOvertime);
				savedInstanceState.putBoolean("_statsAlreadyUploaded2", _statsAlreadyUploaded);
				
				savedInstanceState.putStringArrayList("_rulesPlayer2", _rules);
				
				savedInstanceState.putInt("_opponentCupsRemaining2", _opponentCupsRemaining);
				savedInstanceState.putInt("_ownCupsRemaining2", _ownCupsRemaining);
				
				savedInstanceState.putFloat("_eloRating2", _eloRating);
				savedInstanceState.putInt("_cupDifferential2", _cupDifferential);
				savedInstanceState.putInt("_gamesPlayed2", _gamesPlayed);
				savedInstanceState.putInt("_rank2", _rank);
				
				savedInstanceState.putInt("stat_currentHitStreak2", stat_currentHitStreak);
				savedInstanceState.putInt("stat_currentMissStreak2", stat_currentMissStreak);
				savedInstanceState.putInt("stat_highestHitStreak2", stat_highestHitStreak);
				savedInstanceState.putInt("stat_highestMissStreak2", stat_highestMissStreak);
				savedInstanceState.putInt("stat_shotsTaken2", stat_shotsTaken);
				savedInstanceState.putInt("stat_shotsHit2", stat_shotsHit);
				savedInstanceState.putInt("stat_bouncesHit2", stat_bouncesHit);
				savedInstanceState.putInt("stat_gangBangsHit2", stat_gangBangsHit);
				savedInstanceState.putInt("stat_errorsCommitted2", stat_errorsCommitted);
				savedInstanceState.putInt("stat_heatingUp2", stat_heatingUp);
				savedInstanceState.putInt("stat_onFire2", stat_onFire);
				savedInstanceState.putInt("stat_redemptionShotsTaken2", stat_redemptionShotsTaken);
				savedInstanceState.putInt("stat_redemptionShotsHit2", stat_redemptionShotsHit);
				savedInstanceState.putInt("stat_redemptionAttempts2", stat_redemptionAttempts);
				savedInstanceState.putInt("stat_redemptionSuccesses2", stat_redemptionSuccesses);
				
				savedInstanceState.putIntegerArrayList("stat_shotsPerCup2", stat_shotsPerCup);
				savedInstanceState.putIntegerArrayList("stat_hitsPerCup2", stat_hitsPerCup);
				
				savedInstanceState.putBoolean("stat_redemptionInProgress2", stat_redemptionInProgress);
			}
				break;
				
			case 3:
			{
				savedInstanceState.putString("_name3", _name);
				savedInstanceState.putInt("_teamID3", _teamID);
				savedInstanceState.putInt("_activeShotType3", _activeShotType);
				savedInstanceState.putBoolean("_isActiveShooter3", _isActiveShooter);
				savedInstanceState.putBoolean("_hasEnteredOvertime3", _hasEnteredOvertime);
				savedInstanceState.putBoolean("_statsAlreadyUploaded3", _statsAlreadyUploaded);
				
				savedInstanceState.putStringArrayList("_rulesPlayer3", _rules);
				
				savedInstanceState.putInt("_opponentCupsRemaining3", _opponentCupsRemaining);
				savedInstanceState.putInt("_ownCupsRemaining3", _ownCupsRemaining);
				
				savedInstanceState.putFloat("_eloRating3", _eloRating);
				savedInstanceState.putInt("_cupDifferential3", _cupDifferential);
				savedInstanceState.putInt("_gamesPlayed3", _gamesPlayed);
				savedInstanceState.putInt("_rank3", _rank);
				
				savedInstanceState.putInt("stat_currentHitStreak3", stat_currentHitStreak);
				savedInstanceState.putInt("stat_currentMissStreak3", stat_currentMissStreak);
				savedInstanceState.putInt("stat_highestHitStreak3", stat_highestHitStreak);
				savedInstanceState.putInt("stat_highestMissStreak3", stat_highestMissStreak);
				savedInstanceState.putInt("stat_shotsTaken3", stat_shotsTaken);
				savedInstanceState.putInt("stat_shotsHit3", stat_shotsHit);
				savedInstanceState.putInt("stat_bouncesHit3", stat_bouncesHit);
				savedInstanceState.putInt("stat_gangBangsHit3", stat_gangBangsHit);
				savedInstanceState.putInt("stat_errorsCommitted3", stat_errorsCommitted);
				savedInstanceState.putInt("stat_heatingUp3", stat_heatingUp);
				savedInstanceState.putInt("stat_onFire3", stat_onFire);
				savedInstanceState.putInt("stat_redemptionShotsTaken3", stat_redemptionShotsTaken);
				savedInstanceState.putInt("stat_redemptionShotsHit3", stat_redemptionShotsHit);
				savedInstanceState.putInt("stat_redemptionAttempts3", stat_redemptionAttempts);
				savedInstanceState.putInt("stat_redemptionSuccesses3", stat_redemptionSuccesses);
				
				savedInstanceState.putIntegerArrayList("stat_shotsPerCup3", stat_shotsPerCup);
				savedInstanceState.putIntegerArrayList("stat_hitsPerCup3", stat_hitsPerCup);
				
				savedInstanceState.putBoolean("stat_redemptionInProgress3", stat_redemptionInProgress);
			}
				break;
				
			default:
				break;
		}
		
	}
	
	public void reloadVariablesFromSaveState(Bundle savedInstanceState)
	{
		switch (_playerID)
		{
			case 0:
			{
				_name = savedInstanceState.getString("_name0");
				_teamID = savedInstanceState.getInt("_teamID0");
				_activeShotType = savedInstanceState.getInt("_activeShotType0");
				_isActiveShooter = savedInstanceState.getBoolean("_isActiveShooter0");
				_hasEnteredOvertime = savedInstanceState.getBoolean("_hasEnteredOvertime0");
				_statsAlreadyUploaded = savedInstanceState.getBoolean("_statsAlreadyUploaded0");
				
				_rules = savedInstanceState.getStringArrayList("_rulesPlayer0");
				
				_opponentCupsRemaining = savedInstanceState.getInt("_opponentCupsRemaining0");
				_ownCupsRemaining = savedInstanceState.getInt("_ownCupsRemaining0");
				
				_eloRating = savedInstanceState.getFloat("_eloRating0");
				_cupDifferential = savedInstanceState.getInt("_cupDifferential0");
				_gamesPlayed = savedInstanceState.getInt("_gamesPlayed0");
				_rank = savedInstanceState.getInt("_rank0");
				
				stat_currentHitStreak = savedInstanceState.getInt("stat_currentHitStreak0");
				stat_currentMissStreak = savedInstanceState.getInt("stat_currentMissStreak0");
				stat_highestHitStreak = savedInstanceState.getInt("stat_highestHitStreak0");
				stat_highestMissStreak = savedInstanceState.getInt("stat_highestMissStreak0");
				stat_shotsTaken = savedInstanceState.getInt("stat_shotsTaken0");
				stat_shotsHit = savedInstanceState.getInt("stat_shotsHit0");
				stat_bouncesHit = savedInstanceState.getInt("stat_bouncesHit0");
				stat_gangBangsHit = savedInstanceState.getInt("stat_gangBangsHit0");
				stat_errorsCommitted = savedInstanceState.getInt("stat_errorsCommitted0");
				stat_heatingUp = savedInstanceState.getInt("stat_heatingUp0");
				stat_onFire = savedInstanceState.getInt("stat_onFire0");
				stat_redemptionShotsTaken = savedInstanceState.getInt("stat_redemptionShotsTaken0");
				stat_redemptionShotsHit = savedInstanceState.getInt("stat_redemptionShotsHit0");
				stat_redemptionAttempts = savedInstanceState.getInt("stat_redemptionAttempts0");
				stat_redemptionSuccesses = savedInstanceState.getInt("stat_redemptionSuccesses0");
				
				stat_shotsPerCup = savedInstanceState.getIntegerArrayList("stat_shotsPerCup0");
				stat_hitsPerCup = savedInstanceState.getIntegerArrayList("stat_hitsPerCup0");
				
				stat_redemptionInProgress = savedInstanceState.getBoolean("stat_redemptionInProgress0");
			}
				break;
			
			case 1:
			{
				_name = savedInstanceState.getString("_name1");
				_teamID = savedInstanceState.getInt("_teamID1");
				_activeShotType = savedInstanceState.getInt("_activeShotType1");
				_isActiveShooter = savedInstanceState.getBoolean("_isActiveShooter1");
				_hasEnteredOvertime = savedInstanceState.getBoolean("_hasEnteredOvertime1");
				_statsAlreadyUploaded = savedInstanceState.getBoolean("_statsAlreadyUploaded1");
				
				_rules = savedInstanceState.getStringArrayList("_rulesPlayer1");
				
				_opponentCupsRemaining = savedInstanceState.getInt("_opponentCupsRemaining1");
				_ownCupsRemaining = savedInstanceState.getInt("_ownCupsRemaining1");
				
				_eloRating = savedInstanceState.getFloat("_eloRating1");
				_cupDifferential = savedInstanceState.getInt("_cupDifferential1");
				_gamesPlayed = savedInstanceState.getInt("_gamesPlayed1");
				_rank = savedInstanceState.getInt("_rank1");
				
				stat_currentHitStreak = savedInstanceState.getInt("stat_currentHitStreak1");
				stat_currentMissStreak = savedInstanceState.getInt("stat_currentMissStreak1");
				stat_highestHitStreak = savedInstanceState.getInt("stat_highestHitStreak1");
				stat_highestMissStreak = savedInstanceState.getInt("stat_highestMissStreak1");
				stat_shotsTaken = savedInstanceState.getInt("stat_shotsTaken1");
				stat_shotsHit = savedInstanceState.getInt("stat_shotsHit1");
				stat_bouncesHit = savedInstanceState.getInt("stat_bouncesHit1");
				stat_gangBangsHit = savedInstanceState.getInt("stat_gangBangsHit1");
				stat_errorsCommitted = savedInstanceState.getInt("stat_errorsCommitted1");
				stat_heatingUp = savedInstanceState.getInt("stat_heatingUp1");
				stat_onFire = savedInstanceState.getInt("stat_onFire1");
				stat_redemptionShotsTaken = savedInstanceState.getInt("stat_redemptionShotsTaken1");
				stat_redemptionShotsHit = savedInstanceState.getInt("stat_redemptionShotsHit1");
				stat_redemptionAttempts = savedInstanceState.getInt("stat_redemptionAttempts1");
				stat_redemptionSuccesses = savedInstanceState.getInt("stat_redemptionSuccesses1");
				
				stat_shotsPerCup = savedInstanceState.getIntegerArrayList("stat_shotsPerCup1");
				stat_hitsPerCup = savedInstanceState.getIntegerArrayList("stat_hitsPerCup1");
				
				stat_redemptionInProgress = savedInstanceState.getBoolean("stat_redemptionInProgress1");
			}
				break;
			
			case 2:
			{
				_name = savedInstanceState.getString("_name2");
				_teamID = savedInstanceState.getInt("_teamID2");
				_activeShotType = savedInstanceState.getInt("_activeShotType2");
				_isActiveShooter = savedInstanceState.getBoolean("_isActiveShooter2");
				_hasEnteredOvertime = savedInstanceState.getBoolean("_hasEnteredOvertime2");
				_statsAlreadyUploaded = savedInstanceState.getBoolean("_statsAlreadyUploaded2");
				
				_rules = savedInstanceState.getStringArrayList("_rulesPlayer2");
				
				_opponentCupsRemaining = savedInstanceState.getInt("_opponentCupsRemaining2");
				_ownCupsRemaining = savedInstanceState.getInt("_ownCupsRemaining2");
				
				_eloRating = savedInstanceState.getFloat("_eloRating2");
				_cupDifferential = savedInstanceState.getInt("_cupDifferential2");
				_gamesPlayed = savedInstanceState.getInt("_gamesPlayed2");
				_rank = savedInstanceState.getInt("_rank2");
				
				stat_currentHitStreak = savedInstanceState.getInt("stat_currentHitStreak2");
				stat_currentMissStreak = savedInstanceState.getInt("stat_currentMissStreak2");
				stat_highestHitStreak = savedInstanceState.getInt("stat_highestHitStreak2");
				stat_highestMissStreak = savedInstanceState.getInt("stat_highestMissStreak2");
				stat_shotsTaken = savedInstanceState.getInt("stat_shotsTaken2");
				stat_shotsHit = savedInstanceState.getInt("stat_shotsHit2");
				stat_bouncesHit = savedInstanceState.getInt("stat_bouncesHit2");
				stat_gangBangsHit = savedInstanceState.getInt("stat_gangBangsHit2");
				stat_errorsCommitted = savedInstanceState.getInt("stat_errorsCommitted2");
				stat_heatingUp = savedInstanceState.getInt("stat_heatingUp2");
				stat_onFire = savedInstanceState.getInt("stat_onFire2");
				stat_redemptionShotsTaken = savedInstanceState.getInt("stat_redemptionShotsTaken2");
				stat_redemptionShotsHit = savedInstanceState.getInt("stat_redemptionShotsHit2");
				stat_redemptionAttempts = savedInstanceState.getInt("stat_redemptionAttempts2");
				stat_redemptionSuccesses = savedInstanceState.getInt("stat_redemptionSuccesses2");
				
				stat_shotsPerCup = savedInstanceState.getIntegerArrayList("stat_shotsPerCup2");
				stat_hitsPerCup = savedInstanceState.getIntegerArrayList("stat_hitsPerCup2");
				
				stat_redemptionInProgress = savedInstanceState.getBoolean("stat_redemptionInProgress2");
			}
				break;
				
			case 3:
			{
				_name = savedInstanceState.getString("_name3");
				_teamID = savedInstanceState.getInt("_teamID3");
				_activeShotType = savedInstanceState.getInt("_activeShotType3");
				_isActiveShooter = savedInstanceState.getBoolean("_isActiveShooter3");
				_hasEnteredOvertime = savedInstanceState.getBoolean("_hasEnteredOvertime3");
				_statsAlreadyUploaded = savedInstanceState.getBoolean("_statsAlreadyUploaded3");
				
				_rules = savedInstanceState.getStringArrayList("_rulesPlayer3");
				
				_opponentCupsRemaining = savedInstanceState.getInt("_opponentCupsRemaining3");
				_ownCupsRemaining = savedInstanceState.getInt("_ownCupsRemaining3");
				
				_eloRating = savedInstanceState.getFloat("_eloRating3");
				_cupDifferential = savedInstanceState.getInt("_cupDifferential3");
				_gamesPlayed = savedInstanceState.getInt("_gamesPlayed3");
				_rank = savedInstanceState.getInt("_rank3");
				
				stat_currentHitStreak = savedInstanceState.getInt("stat_currentHitStreak3");
				stat_currentMissStreak = savedInstanceState.getInt("stat_currentMissStreak3");
				stat_highestHitStreak = savedInstanceState.getInt("stat_highestHitStreak3");
				stat_highestMissStreak = savedInstanceState.getInt("stat_highestMissStreak3");
				stat_shotsTaken = savedInstanceState.getInt("stat_shotsTaken3");
				stat_shotsHit = savedInstanceState.getInt("stat_shotsHit3");
				stat_bouncesHit = savedInstanceState.getInt("stat_bouncesHit3");
				stat_gangBangsHit = savedInstanceState.getInt("stat_gangBangsHit3");
				stat_errorsCommitted = savedInstanceState.getInt("stat_errorsCommitted3");
				stat_heatingUp = savedInstanceState.getInt("stat_heatingUp3");
				stat_onFire = savedInstanceState.getInt("stat_onFire3");
				stat_redemptionShotsTaken = savedInstanceState.getInt("stat_redemptionShotsTaken3");
				stat_redemptionShotsHit = savedInstanceState.getInt("stat_redemptionShotsHit3");
				stat_redemptionAttempts = savedInstanceState.getInt("stat_redemptionAttempts3");
				stat_redemptionSuccesses = savedInstanceState.getInt("stat_redemptionSuccesses3");
				
				stat_shotsPerCup = savedInstanceState.getIntegerArrayList("stat_shotsPerCup3");
				stat_hitsPerCup = savedInstanceState.getIntegerArrayList("stat_hitsPerCup3");
				
				stat_redemptionInProgress = savedInstanceState.getBoolean("stat_redemptionInProgress3");
			}
				break;
				
			default:
				break;
		}
	}
}
