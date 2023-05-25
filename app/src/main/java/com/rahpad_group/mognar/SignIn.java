package com.rahpad_group.mognar;

import android.Manifest.permission;
import android.annotation.TargetApi;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.Typeface;
import android.net.wifi.WifiManager;
import android.os.Build.VERSION_CODES;
import android.os.Bundle;
import android.support.v4.app.ActivityCompat;
import android.support.v4.content.ContextCompat;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.text.InputType;
import android.text.format.Formatter;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request.Method;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.rahpad_group.mognar.Required_Classes.MySingleton;

import java.io.File;
import java.io.IOException;
import java.util.HashMap;
import java.util.Map;
import java.util.concurrent.ExecutionException;

public class SignIn extends AppCompatActivity {

    final String TAG = this.getClass().getSimpleName();
    private static String[] persianNumbers = new String[]{"۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹"};
    EditText UserNameEt,UserPasswordEt;
    private String ip_Text="10.0.2.2";
    int ACCESS_WIFI_CODE = 145;
    String type = "NULL";
    String inserturl = "http://www.mognar.com/android/runit.php";
    public String getIp_Text() {
        return ip_Text;
    }
    public void setIp_Text(String ip_Text) {
        this.ip_Text = ip_Text;
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_sign_in);
        android.support.v7.app.ActionBar actionBar = getSupportActionBar();
        actionBar.hide();
        String rahpadTextString = toPersianNumber("Copyright \u00a9 Rahpad group");
        TextView tv1 = (TextView) findViewById(R.id.txt_rahpadGroup);
        tv1.setText(rahpadTextString);
        deleteCache(getApplicationContext());
        UserNameEt = (EditText)findViewById(R.id.username);
        UserPasswordEt = (EditText) findViewById(R.id.passwords);
        Typeface custom_font = Typeface.createFromAsset(getAssets(),  "fonts/byekan.ttf");
        UserNameEt.setTypeface(custom_font);
        UserPasswordEt.setTypeface(custom_font);
        Button btnlogin = (Button)findViewById(R.id.SaveButton);
        btnlogin.setTypeface(custom_font);
//WE CAN HAVE AS MANY OF THIS BLOCK AS WE WANT , JUST DON'T FORGET TO GIVE ANOTHER NAME OR NUMBER TO stringReque AND ALSO GIVE ANOTHER URL

    }

    @Override
    protected void onResume() {

        deleteCache(getApplicationContext());
        super.onResume();
    }

    //__________________________________________________________________________________________________________________________________________


    public static String toPersianNumber(String text) {
        if (text.isEmpty())
            return "";
        String out = "";
        int length = text.length();
        for (int i = 0; i < length; i++) {
            char c = text.charAt(i);
            if ('0' <= c && c <= '9') {
                int number = Integer.parseInt(String.valueOf(c));
                out += persianNumbers[number];
            } else if (c == '٫') {
                out += '،';
            } else {
                out += c;
            }

        }
        return out;
    }

    public static void deleteCache(Context context) {
        try {
            File dir = context.getCacheDir();
            deleteDir(dir);
        } catch (Exception e) {}
    }

    public static boolean deleteDir(File dir) {
        if (dir != null && dir.isDirectory()) {
            String[] children = dir.list();
            for (int i = 0; i < children.length; i++) {
                boolean success = deleteDir(new File(dir, children[i]));
                if (!success) {
                    return false;
                }
            }
            return dir.delete();
        } else if(dir!= null && dir.isFile()) {
            return dir.delete();
        } else {
            return false;
        }
    }


    @TargetApi(VERSION_CODES.M)
    public void OnLogin(View view) throws ExecutionException, InterruptedException, IOException {
        if (ContextCompat.checkSelfPermission(SignIn.this, permission.ACCESS_WIFI_STATE) != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(SignIn.this, new String[]{permission.ACCESS_WIFI_STATE}, ACCESS_WIFI_CODE);
        }
        if (ContextCompat.checkSelfPermission(SignIn.this, permission.ACCESS_WIFI_STATE) != PackageManager.PERMISSION_GRANTED) {
            return;
        }
        //GET IP-ADDRESS
        WifiManager wm = (WifiManager) getSystemService(WIFI_SERVICE);
        final String userIp = Formatter.formatIpAddress(wm.getConnectionInfo().getIpAddress());
        //GET Android Device-NAME
        final String deviceName = android.os.Build.MODEL;
        //SET DESCRIPTION
        final String description = "";
        //SET CODE VALUE
        final String codevalue = "1";
        final String secreteriate_idvalue = "0";
        final String userName = UserNameEt.getText().toString();
        final String userPassword = UserPasswordEt.getText().toString();

        //HERE WE GO FOR LOG_REGISTER
        type="log_register";
        if(userName.equals("")) { Toast.makeText(SignIn.this,"لطفا نام کاربری خود را وارد کنید",Toast.LENGTH_LONG).show(); }
        else if(userPassword.equals("")) { Toast.makeText(SignIn.this,"لطفا رمز عبور خود را وارد کنید",Toast.LENGTH_LONG).show(); }
        //USING -------------------------------------ANDROID VOLLEY------------------------------------------------------
        if(!UserNameEt.getText().toString().equals("") && !UserPasswordEt.getText().toString().equals("")) {
            StringRequest stringRequest = new StringRequest(Method.POST, inserturl, new Response.Listener<String>() {
                @Override
                public void onResponse(String response) {
                    if (!response.contains("...") && !response.contains("_")) {
                        Intent intent = new Intent(SignIn.this, MainActivity.class);
                        intent.putExtra("userinformation", response);
                        startActivity(intent);
                    } else if (response.contains("_")) {
                        Toast.makeText(getApplicationContext(),"شما اجازه ی ورود به هیچ دبیرخانه ای را ندارید.", Toast.LENGTH_LONG).show();
                    } else {
                        String arash = response.replace("...,","");
                        Toast.makeText(getApplicationContext(),arash, Toast.LENGTH_LONG).show();
                    }
                    //Toast.makeText(getApplicationContext(),response, Toast.LENGTH_LONG).show();
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    Toast.makeText(getApplicationContext(), "اشکال در برقراری ارتباط با سرور", Toast.LENGTH_LONG).show();
                }
            }) {
                @Override
                protected Map<String, String> getParams() throws AuthFailureError {
                    Map<String, String> params = new HashMap<>();
                    params.put("username_KEY", UserNameEt.getText().toString());
                    params.put("userpass_KEY", UserPasswordEt.getText().toString());
                    params.put("devicename_KEY", deviceName);
                    params.put("userIp_KEY", userIp);
                    params.put("codevalue_KEY", codevalue);
                    params.put("secreteariate_KEY", secreteriate_idvalue);
                    params.put("description_KEY", description);
                    return params;
                }

                @Override
                public Map<String, String> getHeaders() throws AuthFailureError {
                    Map<String, String> headers = new HashMap<>();
                    headers.put("User-Agent", "ArashAndroid");
                    return headers;
                }
            };
            MySingleton.getInstance(getApplicationContext()).addToRequestQueue(stringRequest);
/*            MySingleton.getInstance(getApplicationContext()).getRequestQueue().getCache().remove(inserturl);*/
            MySingleton.getInstance(getApplicationContext()).getRequestQueue().getCache().invalidate(inserturl, true);
/*            MySingleton.getInstance(getApplicationContext()).getRequestQueue().getCache().remove("username_KEY");*/
            MySingleton.getInstance(getApplicationContext()).getRequestQueue().getCache().invalidate("username_KEY", true);
/*            MySingleton.getInstance(getApplicationContext()).getRequestQueue().getCache().remove("userpass_KEY");*/
            MySingleton.getInstance(getApplicationContext()).getRequestQueue().getCache().invalidate("userpass_KEY", true);
        }

        //USING -------------------------------------END OF ANDROID VOLLEY------------------------------------------------


















        /*if(userinfostring.length <= 1) { Toast.makeText(SignIn.this,"یوزرنیم و پسورد اشتباه است",Toast.LENGTH_LONG).show(); }
        else {
            Toast.makeText(SignIn.this,Get_LoginAndSignIn_Answer,Toast.LENGTH_LONG).show();
            Intent intent = new Intent(SignIn.this, MainActivity.class);
            intent.putExtra("userinformation",Get_LoginAndSignIn_Answer);
            startActivity(intent);
        }*/
    }



    public void OnChangeIP(View view) {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle("آی پی کامپیوتر را وارد کنید");

// Set up the input
        final EditText input = new EditText(this);
// Specify the type of input expected; this, for example, sets the input as a password, and will mask the text
        input.setInputType(InputType.TYPE_CLASS_TEXT);
        builder.setView(input);

// Set up the buttons
        builder.setPositiveButton("تغییر", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                setIp_Text(input.getText().toString());
            }
        });
        builder.setNegativeButton("انصراف", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                dialog.cancel();
            }
        });

        builder.show();
    }

}
