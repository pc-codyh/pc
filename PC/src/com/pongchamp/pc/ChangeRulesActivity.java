package com.pongchamp.pc;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.View;
import android.widget.Button;
import android.widget.RadioButton;
import android.widget.TextView;

public class ChangeRulesActivity extends Activity
{
	RadioButton _startingCups6;
	RadioButton _startingCups10;
	RadioButton _bouncesWorth1;
	RadioButton _bouncesWorth2;
	RadioButton _bounceInRedemptionYes;
	RadioButton _bounceInRedemptionNo;
	RadioButton _nbaJamOn;
	RadioButton _nbaJamOff;
	Button _doneButton;
	TextView _rulesPrompt;
	TextView _startingCupsPrompt;
	TextView _bouncesWorthPrompt;
	TextView _bounceInRedempPrompt;
	TextView _nbaJamPrompt;
	
	public void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_changerules);
		
		_startingCups6 = (RadioButton) findViewById(R.id.changerules_cupsToStart_6);
		_startingCups10 = (RadioButton) findViewById(R.id.changerules_cupsToStart_10);
		_bouncesWorth1 = (RadioButton) findViewById(R.id.changerules_bouncesWorth_1);
		_bouncesWorth2 = (RadioButton) findViewById(R.id.changerules_bouncesWorth_2);
		_bounceInRedemptionYes = (RadioButton) findViewById(R.id.changerules_bounceInRedemptionToOT_Yes);
		_bounceInRedemptionNo = (RadioButton) findViewById(R.id.changerules_bounceInRedemptionToOT_No);
		_nbaJamOn = (RadioButton) findViewById(R.id.changerules_nbaJam_On);
		_nbaJamOff = (RadioButton) findViewById(R.id.changerules_nbaJam_Off);
		_doneButton = (Button) findViewById(R.id.changerules_doneButton);
		
		_rulesPrompt = (TextView) findViewById(R.id.changerules_prompt);
		_startingCupsPrompt = (TextView) findViewById(R.id.changerules_cupstostart_prompt);
		_bouncesWorthPrompt = (TextView) findViewById(R.id.changerules_bouncesworth_prompt);
		_bounceInRedempPrompt = (TextView) findViewById(R.id.changerules_bounceinredemp_prompt);
		_nbaJamPrompt = (TextView) findViewById(R.id.changerules_nbajam_prompt);
		
		setDefaultButtonsChecked();
		onDoneButtonPressed();
		
		new Utilities().setFont(getApplicationContext(),
								_rulesPrompt,
								_startingCupsPrompt,
								_bouncesWorthPrompt,
								_bounceInRedempPrompt,
								_nbaJamPrompt,
								_startingCups6,
								_startingCups10,
								_bouncesWorth1,
								_bouncesWorth2,
								_bounceInRedemptionYes,
								_bounceInRedemptionNo,
								_nbaJamOn,
								_nbaJamOff);
	}
	
	private void setDefaultButtonsChecked()
	{
		Bundle extras = getIntent().getExtras();
		String value = null;
		
		if (extras != null)
		{
			value = extras.getString("StartingCups");
			changeCheckedState(value, _startingCups6, _startingCups10);
			
			value = extras.getString("BouncesWorth");
			changeCheckedState(value, _bouncesWorth1, _bouncesWorth2);
			
			value = extras.getString("BounceInRedemption");
			changeCheckedState(value, _bounceInRedemptionYes, _bounceInRedemptionNo);
			
			value = extras.getString("NBAJam");
			changeCheckedState(value, _nbaJamOn, _nbaJamOff);
		}
		else
		{
			_startingCups6.setChecked(true);
			_bouncesWorth1.setChecked(true);
			_bounceInRedemptionYes.setChecked(true);
			_nbaJamOn.setChecked(true);
		}
	}
	
	private void changeCheckedState(String checkedSide, RadioButton btnLeft, RadioButton btnRight)
	{
		if (checkedSide.equals("left"))
		{
			btnLeft.setChecked(true);
			btnRight.setChecked(false);
		}
		else
		{
			btnLeft.setChecked(false);
			btnRight.setChecked(true);
		}
	}
	
	public void onDoneButtonPressed()
	{
		_doneButton.setOnClickListener(new View.OnClickListener()
		{	
			public void onClick(View v)
			{
				endActivity();
			}
		});
	}
	
	public void onRadioButtonClicked(View view)
	{
		boolean isChecked = ((RadioButton) view).isChecked();
		
		switch (view.getId())
		{
			case R.id.changerules_cupsToStart_6:
			{
				if (isChecked)
				{
					_startingCups10.setChecked(false);
				}
			}
				break;
				
			case R.id.changerules_cupsToStart_10:
			{
				if (isChecked)
				{
					_startingCups6.setChecked(false);
				}
			}
				break;
				
			case R.id.changerules_bouncesWorth_1:
			{
				if (isChecked)
				{
					_bouncesWorth2.setChecked(false);
				}
			}
				break;
				
			case R.id.changerules_bouncesWorth_2:
			{
				if (isChecked)
				{
					_bouncesWorth1.setChecked(false);
				}
			}
				break;
				
			case R.id.changerules_bounceInRedemptionToOT_Yes:
			{
				if (isChecked)
				{
					_bounceInRedemptionNo.setChecked(false);
				}
			}
				break;
				
			case R.id.changerules_bounceInRedemptionToOT_No:
			{
				if (isChecked)
				{
					_bounceInRedemptionYes.setChecked(false);
				}
			}
				break;
				
			case R.id.changerules_nbaJam_On:
			{
				if (isChecked)
				{
					_nbaJamOff.setChecked(false);
				}
			}
				break;
				
			case R.id.changerules_nbaJam_Off:
			{
				if (isChecked)
				{
					_nbaJamOn.setChecked(false);
				}
			}
				break;
				
			default:
				break;
		}
	}
	
	/*
	 * The RadioButton on the physical left should always be passed
	 * in to the following function.
	 */
	private String getRuleValue(RadioButton btn)
	{
		if (btn.isChecked())
		{
			return "left";
		}
		
		return "right";
	}
	
	private void endActivity()
	{
		Intent intent = new Intent(ChangeRulesActivity.this, MenuActivity.class);
		
		intent.putExtra("StartingCups", getRuleValue(_startingCups6));
		intent.putExtra("BouncesWorth", getRuleValue(_bouncesWorth1));
		intent.putExtra("BounceInRedemption", getRuleValue(_bounceInRedemptionYes));
		intent.putExtra("NBAJam", getRuleValue(_nbaJamOn));
		
		setResult(LoginActivity.RESULT_OK, intent);
		
		finish();
	}
	
	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event)
	{
		if (keyCode == KeyEvent.KEYCODE_BACK)
		{
			endActivity();
		}
		
		return true;
	}
}
