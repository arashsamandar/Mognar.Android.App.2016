package com.rahpad_group.mognar.Fragment;

import android.app.Fragment;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.rahpad_group.mognar.R;

/**
 * Created by Arash on 12/31/2016.
 */
public class Fragment3 extends Fragment {
    public Fragment3() {
    }

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment3,container,false);
        return rootView;
    }
}
