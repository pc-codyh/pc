package com.pongchamp.pc;

import android.app.Activity;
import android.os.Bundle;
import android.widget.TextView;

public class AchievementsActivity extends Activity
{	
	TextView _title;
	TextView _good;
	TextView _shs, _shsDesc;
	TextView _mj, _mjDesc;
	TextView _tkcp, _tkcpDesc;
	TextView _hc, _hcDesc;
	TextView _cwtpd, _cwtpdDesc;
	TextView _ps, _psDesc;
	TextView _per, _perDesc;
	TextView _dbno, _dbnoDesc;
	TextView _mar, _marDesc;
	TextView _fdm, _fdmDesc;
	TextView _bad;
	TextView _ck, _ckDesc;
	TextView _bb, _bbDesc;
	TextView _bc, _bcDesc;
	TextView _ugly;
	TextView _bank, _bankDesc;
	TextView _skunk, _skunkDesc;
	TextView _sw, _swDesc;
	TextView _unlock;
	
	public void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_achievements);
		
		_title = (TextView) findViewById(R.id.ach_title);
		_good = (TextView) findViewById(R.id.ach_good);
		_shs = (TextView) findViewById(R.id.ach_shs); _shsDesc = (TextView) findViewById(R.id.ach_shs_desc);
		_mj = (TextView) findViewById(R.id.ach_mj); _mjDesc = (TextView) findViewById(R.id.ach_mj_desc);
		_tkcp = (TextView) findViewById(R.id.ach_tkcp); _tkcpDesc = (TextView) findViewById(R.id.ach_tkcp_desc);
		_hc = (TextView) findViewById(R.id.ach_hc); _hcDesc = (TextView) findViewById(R.id.ach_hc_desc);
		_cwtpd = (TextView) findViewById(R.id.ach_cwtpd); _cwtpdDesc = (TextView) findViewById(R.id.ach_cwtpd_desc);
		_ps = (TextView) findViewById(R.id.ach_ps); _psDesc = (TextView) findViewById(R.id.ach_ps_desc);
		_per = (TextView) findViewById(R.id.ach_per); _perDesc = (TextView) findViewById(R.id.ach_per_desc);
		_dbno = (TextView) findViewById(R.id.ach_dbno); _dbnoDesc = (TextView) findViewById(R.id.ach_dbno_desc);
		_mar = (TextView) findViewById(R.id.ach_mar); _marDesc = (TextView) findViewById(R.id.ach_mar_desc);
		_fdm = (TextView) findViewById(R.id.ach_fdm); _fdmDesc = (TextView) findViewById(R.id.ach_fdm_desc);
		_bad = (TextView) findViewById(R.id.ach_bad);
		_ck = (TextView) findViewById(R.id.ach_ck); _ckDesc = (TextView) findViewById(R.id.ach_ck_desc);
		_bb = (TextView) findViewById(R.id.ach_bb); _bbDesc = (TextView) findViewById(R.id.ach_bb_desc);
		_bc = (TextView) findViewById(R.id.ach_bc); _bcDesc = (TextView) findViewById(R.id.ach_bc_desc);
		_ugly = (TextView) findViewById(R.id.ach_ugly);
		_bank = (TextView) findViewById(R.id.ach_bank); _bankDesc = (TextView) findViewById(R.id.ach_bank_desc);
		_skunk = (TextView) findViewById(R.id.ach_skunk); _skunkDesc = (TextView) findViewById(R.id.ach_skunk_desc);
		_sw = (TextView) findViewById(R.id.ach_sw); _swDesc = (TextView) findViewById(R.id.ach_sw_desc);
		_unlock = (TextView) findViewById(R.id.ach_unlock);
		
		new Utilities().setFont(getApplicationContext(),
								_title,
								_good,
								_shs, _shsDesc,
								_mj, _mjDesc,
								_tkcp, _tkcpDesc,
								_hc, _hcDesc,
								_cwtpd, _cwtpdDesc,
								_ps, _psDesc,
								_per, _perDesc,
								_dbno, _dbnoDesc,
								_mar, _marDesc,
								_fdm, _fdmDesc,
								_bad,
								_ck, _ckDesc,
								_bb, _bbDesc,
								_bc, _bcDesc,
								_ugly,
								_bank, _bankDesc,
								_skunk, _skunkDesc,
								_sw, _swDesc,
								_unlock);
	}
}
