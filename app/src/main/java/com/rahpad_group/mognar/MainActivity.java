package com.rahpad_group.mognar;

import android.annotation.TargetApi;
import android.app.Fragment;
import android.app.FragmentManager;
import android.app.FragmentTransaction;
import android.os.Build;
import android.os.Bundle;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.TextView;

import com.rahpad_group.mognar.Fragment.Fragment1;
import com.rahpad_group.mognar.Fragment.Fragment2;
import com.rahpad_group.mognar.Fragment.Fragment3;
import com.rahpad_group.mognar.adapter.SlidingMenuAdapter;
import com.rahpad_group.mognar.model.ItemSlideMenu;

import java.util.ArrayList;
import java.util.List;

public class MainActivity extends AppCompatActivity {

    private List<ItemSlideMenu> listSliding;
    private SlidingMenuAdapter adapter;
    private ListView listViewSliding;
    private DrawerLayout drawerLayout;
    private ActionBarDrawerToggle actionBarDrawerToggle;
    TextView userInfo;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        forceRTLIfSupported();
        super.onCreate(savedInstanceState);
        setContentView(R.layout.main_activity);
        userInfo = (TextView)findViewById(R.id.txt_user_info);
        String[] userinfostring = getIntent().getExtras().getString("userinformation").split(",");
        String deletethis = getIntent().getExtras().getString("userinformation");
        String hisher="";
        if(userinfostring[2].equals("مرد")) { hisher = "آقای";} else {hisher = "خانم";}
        if(userinfostring[2].equals("men")) { hisher = "آقای";} else if(userinfostring[3].equals("female")) {hisher = "خانم";}
        String welcomeUser = "با سلام "+ hisher + " " + userinfostring[0] + " " + userinfostring[1] + " " + "شما با موفقیت وارد سیستم شدید";
        userInfo.setText(welcomeUser);
        setTitle(userinfostring[3]);
        //Init component
        listViewSliding = (ListView) findViewById(R.id.lv_sliding_menu);
        drawerLayout = (DrawerLayout) findViewById(R.id.drawer_layout);
        listSliding = new ArrayList<>();
        //Add item for sliding list
        listSliding.add(new ItemSlideMenu(R.mipmap.namehaye_daryafty, "نامه های دریافتی"));
        listSliding.add(new ItemSlideMenu(R.mipmap.namehaye_ersaly, "نامه های ارسالی"));
        listSliding.add(new ItemSlideMenu(R.mipmap.searchings, "جستجو"));
        listSliding.add(new ItemSlideMenu(R.mipmap.change_password, "تغییر رمز"));
        listSliding.add(new ItemSlideMenu(R.mipmap.about_gonar, "درباره موگنار"));
        listSliding.add(new ItemSlideMenu(R.mipmap.exit_icon, "خروج"));
        adapter = new SlidingMenuAdapter(this, listSliding);
        listViewSliding.setAdapter(adapter);

        //Display icon to open/ close sliding list
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        //Set title ( ENABLE IF YOU WANT TO SEE ON WICH MENU ITEM YOU ARE ( I MEAN YOU CLICKED IF YOU WILL ) .
        //setTitle(listSliding.get(0).getTitle());
        //item selected
        listViewSliding.setItemChecked(0, true);
        //Close menu
        drawerLayout.closeDrawer(listViewSliding);

        //Display fragment 1 when start
        //replaceFragment(0);
        //Hanlde on item click

        listViewSliding.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {

                //Set title ( ENABLE IF YOU WANT TO SEE ON WICH MENU ITEM YOU ARE ( I MEAN YOU CLICKED IF YOU WILL ) .
                //setTitle(listSliding.get(position).getTitle());

                //item selected
                listViewSliding.setItemChecked(position, true);
                //Replace fragment
                replaceFragment(position);
                //Close menu
                drawerLayout.closeDrawer(listViewSliding);
            }
        });

        actionBarDrawerToggle = new ActionBarDrawerToggle(this, drawerLayout, R.string.drawer_opened, R.string.drawer_closed){

            @Override
            public void onDrawerOpened(View drawerView) {
                super.onDrawerOpened(drawerView);
                invalidateOptionsMenu();
            }

            @Override
            public void onDrawerClosed(View drawerView) {
                super.onDrawerClosed(drawerView);
                invalidateOptionsMenu();
            }
        };

        drawerLayout.setDrawerListener(actionBarDrawerToggle);
    }

    @TargetApi(Build.VERSION_CODES.JELLY_BEAN_MR1)
    private void forceRTLIfSupported()
    {
        if(Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN_MR1){
            getWindow().getDecorView().setLayoutDirection(View.LAYOUT_DIRECTION_RTL);
        }
    }



    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.main, menu);
        return super.onCreateOptionsMenu(menu);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {

        if(actionBarDrawerToggle.onOptionsItemSelected(item)) {
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    @Override
    protected void onPostCreate(Bundle savedInstanceState) {
        super.onPostCreate(savedInstanceState);
        actionBarDrawerToggle.syncState();
    }

    //Create method replace fragment

    private void replaceFragment(int pos) {
        Fragment fragment = null;
        switch (pos) {
            case 0:
                fragment = new Fragment1();
                break;
            case 1:
                fragment = new Fragment2();
                break;
            case 2:
                fragment = new Fragment3();
                break;
            default:
                fragment = new Fragment1();
                break;
        }

        if(null!=fragment) {
            FragmentManager fragmentManager = getFragmentManager();
            FragmentTransaction transaction = fragmentManager.beginTransaction();
            transaction.replace(R.id.main_content, fragment);
            transaction.addToBackStack(null);
            transaction.commit();
        }
    }
}
