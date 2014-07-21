package com.pongchamp.pc;

import java.util.ArrayList;
import java.util.Collections;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Color;
import android.net.Uri;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

@SuppressLint({ "NewApi", "ShowToast" })
public class MenuActivity extends Activity
{
	PCUser _activeUser = null;
	TextView _activeUsername = null;
	
	ListView _mainMenu;
	
	/* 
	 * The following array holds the values of the selected rules
	 * chosen in the ChangeRulesActivity. A value of 'left' means
	 * the RadioButton on the left is selected. A value of 'right'
	 * means the RadioButton on the right is selected.
	 */
	ArrayList<NameValuePair> _rules = null;
	ArrayList<String> _players = null;
	
	final int ID_LOGIN = 1;
	final int ID_RULES = 2;
	final int ID_PLAY = 3;
	final int ID_CREATE_PLAYER = 4;
	
	final int REGISTER 		= 0;
	final int LOGIN			= 1;
	final int HELP			= 2;
	
	final int LOGOUT		= 0;
	final int VIEWSTATS		= 1;
	final int ACHIEVEMENTS	= 3;
	final int CREATEPLAYER	= 4;
	final int RANDOMTEAMS	= 5;
	final int CHANGERULES	= 6;
	final int PLAYGAME		= 7;
	
	public enum RulesEnum
	{
		STARTING_CUPS(0),
		BOUNCES_WORTH(1),
		BOUNCE_IN_REDEMP(2),
		NBA_JAM(3);
		
		private final int id;
		
		RulesEnum(int id)
		{
			this.id = id;
		}
		
		public int getValue()
		{
			return id;
		}
	}
		
    @Override
    public void onCreate(Bundle savedInstanceState)
    {
    	setTitle("");
        setTheme(R.style.AppTheme);
        
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_menu);
        
        _activeUsername = (TextView) findViewById(R.id.menu_activeUsername);
        
        _mainMenu = (ListView) findViewById(R.id.mainmenu_list);
        
        onListViewItemClick();
        
        handleInputParams(null);
        applyRuleChanges(null);
        
        new Utilities().setFont(getApplicationContext(), _activeUsername);
    }
    
    @Override
    public void onSaveInstanceState(Bundle savedInstanceState)
    {
    	super.onSaveInstanceState(savedInstanceState);
    	
    	if (_activeUser != null) // If the user is logged in.
    	{
    		savedInstanceState.putString("activeUsername", _activeUser.getUsername());
    		savedInstanceState.putStringArrayList("players", _players);
    		savedInstanceState.putBoolean("userWasLoggedIn", true);
    		
			savedInstanceState.putString("StartingCups", _rules.get(0).getValue());
			savedInstanceState.putString("BouncesWorth", _rules.get(1).getValue());
			savedInstanceState.putString("BounceInRedemption", _rules.get(2).getValue());
			savedInstanceState.putString("NBAJam", _rules.get(3).getValue());
    	}
    	else
    	{
    		savedInstanceState.putBoolean("userWasLoggedIn", false);
    	}
    }
    
    @Override
    public void onRestoreInstanceState(Bundle savedInstanceState)
    {
    	super.onRestoreInstanceState(savedInstanceState);
    	
    	if (savedInstanceState.getBoolean("userWasLoggedIn")) // If the user was logged in.
    	{
    		handleInputParams(savedInstanceState.getString("activeUsername"));
    		loadCachedPlayers(savedInstanceState.getStringArrayList("players"));
    		
    		try
    		{
    			_rules = new ArrayList<NameValuePair>(4);
    			
    			_rules.add(new BasicNameValuePair("StartingCups", savedInstanceState.getString("StartingCups")));
    			_rules.add(new BasicNameValuePair("BouncesWorth", savedInstanceState.getString("BouncesWorth")));
    			_rules.add(new BasicNameValuePair("BounceInRedemption", savedInstanceState.getString("BounceInRedemption")));
    			_rules.add(new BasicNameValuePair("NBAJam", savedInstanceState.getString("NBAJam")));
    		}
    		catch (Exception e)
    		{
    			e.printStackTrace();
    		}
    	}
    }
    
    // Function to handle the click event
    // of the ListView.
    private void onListViewItemClick()
    {
    	_mainMenu.setOnItemClickListener(new OnItemClickListener()
    	{
			public void onItemClick(AdapterView<?> adapter, View view, int position, long id)
			{
				// The user is not logged in.
				if (_activeUser == null)
				{
					switch (position)
					{
						case REGISTER:
						{
							onRegisterButtonPressed();
						}
							break;
							
						case LOGIN:
						{
							onLoginButtonPressed();
						}
							break;
							
						case HELP:
						{
							onHelpButtonPressed();
						}
							break;
							
						default:
							break;
					}
				}
				else
				{
					switch (position)
					{
						case LOGOUT:
						{
							onLogoutButtonPressed();
						}
							break;
							
						case VIEWSTATS:
						{
							onViewStatsButtonPressed();
						}
							break;
							
						case HELP:
						{
							onHelpButtonPressed();
						}
							break;
							
						case ACHIEVEMENTS:
						{
							onAchievementsButtonPressed();
						}
							break;
							
						case CREATEPLAYER:
						{
							onCreatePlayerButtonPressed();
						}
							break;
							
						case RANDOMTEAMS:
						{
							onRandomizeTeamsButtonPressed();
						}
							break;
							
						case CHANGERULES:
						{
							onChangeRulesButtonPressed();
						}
							break;
							
						case PLAYGAME:
						{
							onPlayGameButtonPressed();
						}
							break;
							
						default:
							break;
					}
				}
			}
    	});
    }
    
    private void handleInputParams(String username)
    {
    	ArrayList<String> rows = new ArrayList<String>();
        
    	if (username != null)
    	{
    		_activeUsername.setText("Welcome, " + username);
    		
    		_activeUser = new PCUser(username);
    		
    		rows.add("Logout");
            rows.add("View Stats");
            rows.add("Help");
            rows.add("Achievements");
            rows.add("Create Player");
            rows.add("Random Teams");
            rows.add("Change Rules");
            rows.add("Play Game");
    	}
    	else
    	{
    		_activeUsername.setText(R.string.menu_not_logged_in);
    		
    		rows.add("Register");
            rows.add("Login");
            rows.add("Help");
    	}
    	
    	ArrayAdapter<String> adapter = new CustomListAdapter(this, android.R.layout.simple_list_item_1, rows);
        
        for (int i = 0; i < rows.size(); i++)
        {
        	adapter.add("Placeholder");
        }
        
        adapter.setDropDownViewResource(android.R.layout.simple_list_item_1);
        
        _mainMenu.setAdapter(adapter);
    }
    
    private void loadCachedPlayers(ArrayList<String> players)
    {
    	_players = players;
    }
    
    private void applyRuleChanges(Intent intent)
    {
    	if (intent == null)
    	{
    		_rules = new ArrayList<NameValuePair>(4);
    		
    		_rules.add(new BasicNameValuePair("StartingCups", "left"));
    		_rules.add(new BasicNameValuePair("BouncesWorth", "left"));
    		_rules.add(new BasicNameValuePair("BounceInRedemption", "left"));
    		_rules.add(new BasicNameValuePair("NBAJam", "left"));
    	}
    	else if (_rules == null)
    	{
    		_rules = new ArrayList<NameValuePair>(intent.getExtras().size());
    		
    		_rules.add(new BasicNameValuePair("StartingCups", intent.getStringExtra("StartingCups")));
    		_rules.add(new BasicNameValuePair("BouncesWorth", intent.getStringExtra("BouncesWorth")));
    		_rules.add(new BasicNameValuePair("BounceInRedemption", intent.getStringExtra("BounceInRedemption")));
    		_rules.add(new BasicNameValuePair("NBAJam", intent.getStringExtra("NBAJam")));
    	}
    	else
    	{
    		_rules.set(0, new BasicNameValuePair("StartingCups", intent.getStringExtra("StartingCups")));
    		_rules.set(1, new BasicNameValuePair("BouncesWorth", intent.getStringExtra("BouncesWorth")));
    		_rules.set(2, new BasicNameValuePair("BounceInRedemption", intent.getStringExtra("BounceInRedemption")));
    		_rules.set(3, new BasicNameValuePair("NBAJam", intent.getStringExtra("NBAJam")));
    	}
    }
    
    private void addNewPlayer(String newPlayer)
    {
    	_players.add(newPlayer);
    	
    	Collections.sort(_players);
    }
    
    public void onRegisterButtonPressed()
    {
		// The user is not logged in.
		if (_activeUser == null)
		{
			openRegisterWindow();
		}
		else
		{
			onViewStatsButtonPressed();
		}
    }
    
    // Function that opens the user's stats
    // in a separate browser window.
    private void onViewStatsButtonPressed()
    {
    	Uri uri = Uri.parse("http://www.pongchamp.com/overall_stats.php");
		
		Intent intent = new Intent(Intent.ACTION_VIEW, uri);
		
		startActivity(intent);
    }
    
    public void onLoginButtonPressed()
    {
		openLoginWindow();
    }
    
    private void onLogoutButtonPressed()
    {
		AlertDialog.Builder alert = new AlertDialog.Builder(_mainMenu.getContext());
		
		alert.setTitle(R.string.logout_button_title);
		alert.setMessage(R.string.logout_alert_message);
		
		alert.setNegativeButton(R.string.no_button_title, new DialogInterface.OnClickListener()
		{	
			public void onClick(DialogInterface dialog, int which)
			{
				// Do nothing when the window is dismissed.
			}
		});
		
		alert.setPositiveButton(R.string.yes_button_title, new DialogInterface.OnClickListener()
		{	
			public void onClick(DialogInterface dialog, int which)
			{
				logout();
			}
		});
		
		alert.show();
    }
    
    private void logout()
    {
    	_activeUsername.setText(R.string.menu_not_logged_in);
    	
    	_activeUsername.setBackgroundColor(Color.parseColor("#FFCC00"));
    	
    	_activeUser = null;
    	
    	handleInputParams(null);
    }
    
    public void onCreatePlayerButtonPressed()
    {
		openCreatePlayerWindow();
    }
    
    public void onRandomizeTeamsButtonPressed()
    {
		openRandomizeTeamsWindow();
    }
    
    public void onChangeRulesButtonPressed()
    {
		openChangeRulesWindow();
    }
    
    public void onPlayGameButtonPressed()
    {
		if (_rules != null)
		{
			openPlayGameWindow();
		}
		else
		{
			Toast.makeText(getApplicationContext(), R.string.rules_not_set, Toast.LENGTH_LONG).show();
		}
    }
    
    public void onHelpButtonPressed()
    {
		openHelpWindow();
    }
    
    public void onAchievementsButtonPressed()
    {
    	openAchievementsWindow();
    }
    
    public void openRegisterWindow()
    {
    	Intent intent = new Intent(MenuActivity.this, RegisterActivity.class);
    	
    	MenuActivity.this.startActivity(intent);
    	
    	overridePendingTransition(R.anim.slide_in_right, R.anim.slide_out_left);
    }
    
    public void openLoginWindow()
    {
    	Intent intent = new Intent(this, LoginActivity.class);
    	
    	MenuActivity.this.startActivityForResult(intent, ID_LOGIN);
    	
    	overridePendingTransition(R.anim.slide_in_right, R.anim.slide_out_left);
    }
    
    public void openCreatePlayerWindow()
    {
    	Intent intent = new Intent(MenuActivity.this, CreatePlayerActivity.class);
    	
    	intent.putExtra("ActiveUsername", _activeUser.getUsername());
    	
    	MenuActivity.this.startActivityForResult(intent, ID_CREATE_PLAYER);
    	
    	overridePendingTransition(R.anim.slide_in_right, R.anim.slide_out_left);
    }
    
    public void openRandomizeTeamsWindow()
    {
    	Intent intent = new Intent(MenuActivity.this, RandomizeTeamsActivity.class);
    	
    	intent.putExtra("ActiveUsername", _activeUser.getUsername());
    	intent.putStringArrayListExtra("Players", _players);
    	
    	intent.putExtra("StartingCups", _rules.get(RulesEnum.STARTING_CUPS.getValue()).getValue().toString());
		intent.putExtra("BouncesWorth", _rules.get(RulesEnum.BOUNCES_WORTH.getValue()).getValue().toString());
		intent.putExtra("BounceInRedemption", _rules.get(RulesEnum.BOUNCE_IN_REDEMP.getValue()).getValue().toString());
		intent.putExtra("NBAJam", _rules.get(RulesEnum.NBA_JAM.getValue()).getValue().toString());
    	
    	MenuActivity.this.startActivity(intent);
    	
    	overridePendingTransition(R.anim.slide_in_right, R.anim.slide_out_left);
    }
    
    public void openChangeRulesWindow()
    {
    	Intent intent = new Intent(MenuActivity.this, ChangeRulesActivity.class);
    	    	
    	if (_rules != null)
    	{
    		intent.putExtra("StartingCups", _rules.get(RulesEnum.STARTING_CUPS.getValue()).getValue().toString());
    		intent.putExtra("BouncesWorth", _rules.get(RulesEnum.BOUNCES_WORTH.getValue()).getValue().toString());
    		intent.putExtra("BounceInRedemption", _rules.get(RulesEnum.BOUNCE_IN_REDEMP.getValue()).getValue().toString());
    		intent.putExtra("NBAJam", _rules.get(RulesEnum.NBA_JAM.getValue()).getValue().toString());
    	}
    	
    	MenuActivity.this.startActivityForResult(intent, ID_RULES);
    	overridePendingTransition(R.anim.slide_in_right, R.anim.slide_out_left);
    }
    
    public void openPlayGameWindow()
    {
    	Intent intent = new Intent(MenuActivity.this, ChooseTeamsActivity.class);
    	
    	intent.putExtra("StartingCups", _rules.get(RulesEnum.STARTING_CUPS.getValue()).getValue().toString());
		intent.putExtra("BouncesWorth", _rules.get(RulesEnum.BOUNCES_WORTH.getValue()).getValue().toString());
		intent.putExtra("BounceInRedemption", _rules.get(RulesEnum.BOUNCE_IN_REDEMP.getValue()).getValue().toString());
		intent.putExtra("NBAJam", _rules.get(RulesEnum.NBA_JAM.getValue()).getValue().toString());
		
		intent.putExtra("ActiveUsername", _activeUser.getUsername());
		intent.putStringArrayListExtra("Players", _players);
		
		MenuActivity.this.startActivityForResult(intent, ID_PLAY);
		overridePendingTransition(R.anim.slide_in_right, R.anim.slide_out_left);
    }
    
    public void openHelpWindow()
    {
    	Intent intent = new Intent(MenuActivity.this, HelpActivity.class);
    	
    	MenuActivity.this.startActivity(intent);
    	
    	overridePendingTransition(R.anim.slide_in_right, R.anim.slide_out_left);
    }
    
    public void openAchievementsWindow()
    {
    	Intent intent = new Intent(MenuActivity.this, AchievementsActivity.class);
    	
    	MenuActivity.this.startActivity(intent);
    	
    	overridePendingTransition(R.anim.slide_in_right, R.anim.slide_out_left);
    }
    
    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data)
    {
    	super.onActivityResult(requestCode, resultCode, data);
    	
    	switch (requestCode)
    	{
    		case ID_LOGIN:
    		{
    			if (resultCode == Activity.RESULT_OK)
    			{
    				handleInputParams(data.getStringExtra("ActiveUsername"));
    				loadCachedPlayers(data.getStringArrayListExtra("Players"));
    			}
    		}
    			break;
    		
    		case ID_RULES:
    		{
    			if (resultCode == Activity.RESULT_OK)
    			{
    				applyRuleChanges(data);
    			}
    		}
    			break;
    			
    		case ID_CREATE_PLAYER:
    		{
    			if (resultCode == Activity.RESULT_OK)
    			{
    				addNewPlayer(data.getExtras().getString("NewPlayerName"));
    			}
    		}
    			break;
    			
    		default:
    			break;
    	}
    }
}
