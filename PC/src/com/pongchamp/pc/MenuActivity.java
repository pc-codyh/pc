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
import android.view.Menu;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

@SuppressLint({ "NewApi", "ShowToast" })
public class MenuActivity extends Activity
{
	PCUser _activeUser = null;
	Button _registerButton = null;
	Button _loginButton = null;
	Button _createPlayerButton = null;
	Button _randomizeTeamsButton = null;
	Button _changeRulesButton = null;
	Button _playGameButton = null;
	Button _helpButton = null;
	TextView _activeUsername = null;
	
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
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_menu);
        
        _registerButton = (Button) findViewById(R.id.menu_registerButton);
        _loginButton = (Button) findViewById(R.id.menu_loginButton);
        _createPlayerButton = (Button) findViewById(R.id.menu_createPlayerButton);
        _randomizeTeamsButton = (Button) findViewById(R.id.menu_teamRandomizerButton);
        _changeRulesButton = (Button) findViewById(R.id.menu_changeRulesButton);
        _playGameButton = (Button) findViewById(R.id.menu_playGameButton);
        _helpButton = (Button) findViewById(R.id.menu_helpButton);
        
        _activeUsername = (TextView) findViewById(R.id.menu_activeUsername);
        
        onRegisterButtonPressed();
        onLoginButtonPressed();
        onCreatePlayerButtonPressed();
        onRandomizeTeamsButtonPressed();
        onChangeRulesButtonPressed();
        onPlayGameButtonPressed();
        onHelpButtonPressed();
        
        disableButtons();
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
    
    private void handleInputParams(String username)
    {
    	if (username != null)
    	{
    		_activeUsername.setText("Logged in as: " + username);
    		
    		_activeUser = new PCUser(username);
    		
    		setupLogoutButton();
    		enableButtons();
    		toggleRegisterButton(true);
    		
    		_activeUsername.setBackgroundColor(Color.parseColor("#FFCC33"));
    	}
    	else
    	{
    		_activeUsername.setText(R.string.menu_not_logged_in);
    	}
    }
    
    // Function to toggle the "Register" button into
    // a "View Stats" button when the user is logged
    // in.
    private void toggleRegisterButton(boolean isLoggedIn)
    {
    	_registerButton.setBackgroundResource((isLoggedIn) ? R.drawable.icn_view_stats : R.drawable.icn_register);
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
    
    private void disableButtons()
    {
        _createPlayerButton.setBackgroundResource(R.drawable.icn_create_player_down);
        _randomizeTeamsButton.setBackgroundResource(R.drawable.icn_randomizer_down);
        _changeRulesButton.setBackgroundResource(R.drawable.icn_rules_down);
        _playGameButton.setBackgroundResource(R.drawable.icn_play_game_down);
//        _helpButton.setBackgroundResource(R.drawable.icn_help_down);
        
        ArrayList<Button> buttons = new ArrayList<Button>();
        
        buttons.add(_createPlayerButton);
        buttons.add(_randomizeTeamsButton);
        buttons.add(_changeRulesButton);
        buttons.add(_playGameButton);
//        buttons.add(_helpButton);
        
        for (Button btn : buttons)
        {
        	btn.setEnabled(false);
        	btn.setTextColor(Color.parseColor("#8F8F8F"));
        }
    }
    
    private void enableButtons()
    {
    	_createPlayerButton.setBackgroundResource(R.drawable.icn_create_player);
        _randomizeTeamsButton.setBackgroundResource(R.drawable.icn_randomizer);
        _changeRulesButton.setBackgroundResource(R.drawable.icn_rules);
        _playGameButton.setBackgroundResource(R.drawable.icn_play_game);
        
        ArrayList<Button> buttons = new ArrayList<Button>();
        
        buttons.add(_createPlayerButton);
        buttons.add(_randomizeTeamsButton);
        buttons.add(_changeRulesButton);
        buttons.add(_playGameButton);
        
        for (Button btn : buttons)
        {
        	btn.setEnabled(true);
        	btn.setTextColor(Color.parseColor("#FFFFFF"));
        }
    }
    
    private void setupLogoutButton()
    {
    	_loginButton.setBackgroundResource(R.drawable.icn_logout);
		
		onLogoutButtonPressed();
    }
    
    private void setupLoginButton()
    {
    	_loginButton.setBackgroundResource(R.drawable.icn_login);
    	
    	onLoginButtonPressed();
    }
    
    public void onRegisterButtonPressed()
    {
    	_registerButton.setOnClickListener(new View.OnClickListener()
    	{	
			public void onClick(View v)
			{
				// The user is not logged in.
				if (_activeUser == null)
				{
					openRegisterWindow();
				}
				else
				{
					Uri uri = Uri.parse("http://www.pongchamp.com/viewstats.php?username=" + _activeUser.getUsername() + "&submit=View+Stats");
					
					Intent intent = new Intent(Intent.ACTION_VIEW, uri);
					
					startActivity(intent);
				}
			}
		});
    }
    
    public void onLoginButtonPressed()
    {
    	_loginButton.setOnClickListener(new View.OnClickListener()
    	{	
			public void onClick(View v)
			{
				openLoginWindow();
			}
		});
    }
    
    private void onLogoutButtonPressed()
    {
    	_loginButton.setOnClickListener(new View.OnClickListener()
    	{   		
			public void onClick(View v)
			{
				AlertDialog.Builder alert = new AlertDialog.Builder(v.getContext());
				
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
						disableButtons();
						logout();
					}
				});
				
				alert.show();
			}
		});
    }
    
    private void logout()
    {
    	_activeUsername.setText(R.string.menu_not_logged_in);
    	
    	_activeUsername.setBackgroundColor(Color.WHITE);
    	
    	_activeUser = null;
    	
    	toggleRegisterButton(false);
    	setupLoginButton();
    }
    
    public void onCreatePlayerButtonPressed()
    {
    	_createPlayerButton.setOnClickListener(new View.OnClickListener()
    	{	
			public void onClick(View v)
			{
				openCreatePlayerWindow();
			}
		});
    }
    
    public void onRandomizeTeamsButtonPressed()
    {
    	_randomizeTeamsButton.setOnClickListener(new View.OnClickListener()
    	{	
			public void onClick(View v)
			{
				openRandomizeTeamsWindow();
			}
		});
    }
    
    public void onChangeRulesButtonPressed()
    {
    	_changeRulesButton.setOnClickListener(new View.OnClickListener()
    	{    		
			public void onClick(View v)
			{
				openChangeRulesWindow();
			}
		});
    }
    
    public void onPlayGameButtonPressed()
    {
    	_playGameButton.setOnClickListener(new View.OnClickListener()
    	{	
			public void onClick(View v)
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
		});
    }
    
    public void onHelpButtonPressed()
    {
    	_helpButton.setOnClickListener(new View.OnClickListener()
    	{	
			public void onClick(View v)
			{
				openHelpWindow();
			}
		});
    }
    
    public void openRegisterWindow()
    {
    	Intent intent = new Intent(MenuActivity.this, RegisterActivity.class);
    	
    	MenuActivity.this.startActivity(intent);
    }
    
    public void openLoginWindow()
    {
    	Intent intent = new Intent(this, LoginActivity.class);
    	
    	MenuActivity.this.startActivityForResult(intent, ID_LOGIN);
    }
    
    public void openCreatePlayerWindow()
    {
    	Intent intent = new Intent(MenuActivity.this, CreatePlayerActivity.class);
    	
    	intent.putExtra("ActiveUsername", _activeUser.getUsername());
    	
    	MenuActivity.this.startActivityForResult(intent, ID_CREATE_PLAYER);
    }
    
    public void openRandomizeTeamsWindow()
    {
    	Intent intent = new Intent(MenuActivity.this, RandomizeTeamsActivity.class);
    	
    	intent.putExtra("ActiveUsername", _activeUser.getUsername());
    	intent.putStringArrayListExtra("Players", _players);
    	
    	MenuActivity.this.startActivity(intent);
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
    }
    
    public void openHelpWindow()
    {
    	Intent intent = new Intent(MenuActivity.this, HelpActivity.class);
    	
    	MenuActivity.this.startActivity(intent);
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
