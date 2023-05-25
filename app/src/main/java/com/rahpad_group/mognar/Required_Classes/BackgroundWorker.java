package com.rahpad_group.mognar.Required_Classes;

import android.content.Context;
import android.os.AsyncTask;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLEncoder;

/**
 * Created by Arash on 1/2/2017.
 */
public class BackgroundWorker extends AsyncTask<String,Void,String> {
    Context context;
    String result = "";
    public BackgroundWorker(Context ctx) {
        context = ctx;
    }

    @Override
    protected String doInBackground(String... params) {
        //type,userName,deviceName,getIp_Text(),userIp,codevalue,secreteriate_idvalue,description
        String type = params[0];
        String ipaddress = params[3];
        String login_url = "http://10.0.2.2/AndroidSignIn.php";
        String log_register_url = "http://10.0.2.2/AndroidRegister.php";
        if (!params[3].equals("10.0.2.2")) {
            login_url = "http://" + params[3] + "/AndroidSignIn.php";
            log_register_url = "http://" + params[3] + "/AndroidRegister.php";
        }
        if (type.equals("login")) {
            try {
                String username = params[1];
                String passwords = params[2];
                URL url = new URL(login_url);
                HttpURLConnection httpURLConnection = (HttpURLConnection) url.openConnection();
                httpURLConnection.setRequestMethod("POST");
                httpURLConnection.setDoOutput(true);
                httpURLConnection.setDoInput(true);
                OutputStream outputStream = httpURLConnection.getOutputStream();
                BufferedWriter bufferedWriter = new BufferedWriter(new OutputStreamWriter(outputStream, "UTF-8"));
                String post_data = URLEncoder.encode("username_KEY", "UTF-8") + "=" + URLEncoder.encode(username, "UTF-8") + "&"
                        + URLEncoder.encode("userpass_KEY", "UTF-8") + "=" + URLEncoder.encode(passwords, "UTF-8");
                bufferedWriter.write(post_data);
                bufferedWriter.flush();
                bufferedWriter.close();
                outputStream.close();
                InputStream inputStream = httpURLConnection.getInputStream();
                BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(inputStream, "UTF-8"));
                String line = "";
                while ((line = bufferedReader.readLine()) != null) {
                    result += line;
                }
                bufferedReader.close();
                inputStream.close();
                httpURLConnection.disconnect();
                return result;
            } catch (MalformedURLException e) {
                e.printStackTrace();
            } catch (IOException e) {
                e.printStackTrace();
            }
        } else if (type.equals("log_register")) {
            try {
                //type,userName,deviceName,getIp_Text(),userIp,codevalue,secreteriate_idvalue,description
                String username = params[1];
                String deviceName = params[2];
                String userIp = params[4];
                String codevalue = params[5];
                String secreteriate_idvalue = params[6];
                String description = params[7];
                String userpass = params[8];
//                byte[] data = imagearray.getBytes("UTF-8");
//                String base64 = Base64.encodeToString(data, Base64.DEFAULT);
                URL url = new URL(log_register_url);
                HttpURLConnection httpURLConnection = (HttpURLConnection) url.openConnection();
                httpURLConnection.setRequestMethod("POST");
                httpURLConnection.setDoOutput(true);
                httpURLConnection.setDoInput(true);
                OutputStream outputStream = httpURLConnection.getOutputStream();
                //type,userName,deviceName,getIp_Text(),userIp,codevalue,secreteriate_idvalue,description
                BufferedWriter bufferedWriter = new BufferedWriter(new OutputStreamWriter(outputStream, "UTF-8"));
                String post_data = URLEncoder.encode("username_KEY", "UTF-8") + "=" + URLEncoder.encode(username, "UTF-8") + "&" +
                        URLEncoder.encode("devicename_KEY", "UTF-8") + "=" + URLEncoder.encode(deviceName, "UTF-8") + "&"
                        + URLEncoder.encode("userIp_KEY", "UTF-8") + "=" + URLEncoder.encode(userIp, "UTF-8") +
                        "&" + URLEncoder.encode("codevalue_KEY", "UTF-8") + "=" + URLEncoder.encode(codevalue, "UTF-8") +
                        "&" + URLEncoder.encode("secreteariate_KEY", "UTF-8") + "=" + URLEncoder.encode(secreteriate_idvalue, "UTF-8") +
                        "&" + URLEncoder.encode("description_KEY", "UTF-8") + "=" + URLEncoder.encode(description, "UTF-8") +
                        "&" + URLEncoder.encode("userpass_KEY", "UTF-8") + "=" + URLEncoder.encode(userpass, "UTF-8");
                bufferedWriter.write(post_data);
                bufferedWriter.flush();
                bufferedWriter.close();
                outputStream.close();
                InputStream inputStream = httpURLConnection.getInputStream();
                BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(inputStream, "UTF-8"));
                String line = "";
                while ((line = bufferedReader.readLine()) != null) {
                    result += line;
                }
                bufferedReader.close();
                inputStream.close();
                httpURLConnection.disconnect();
                return result;
            } catch (MalformedURLException e) {
                e.printStackTrace();
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
        return null;
    }

    @Override
    protected void onPreExecute() {
        /*alertDialog = new AlertDialog.Builder(context).create();
        alertDialog.setTitle("Login Status");*/
    }

    @Override
    protected void onPostExecute(String result) {
        /*alertDialog.setMessage(result);
        alertDialog.show();*/
    }

    @Override
    protected void onProgressUpdate(Void... values) {
        super.onProgressUpdate(values);
    }
}
