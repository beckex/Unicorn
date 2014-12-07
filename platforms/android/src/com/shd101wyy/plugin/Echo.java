package com.shd101wyy.plugin;

import org.apache.cordova.CordovaPlugin;
import org.apache.cordova.CallbackContext;
import org.apache.cordova.PluginResult;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

/**
 * This class echoes a string called from JavaScript.
 */
public class Echo extends CordovaPlugin {
	 
	
	//public native String stringFromJNI(String username, String passwor);
	public native String[] calculateSatisfiedDistance(String[] id_ptr, int id_length, double[] array_ptr, int array_length);
	static {
		System.loadLibrary("calculateDistance");
	}
	
    @Override
    public boolean execute(String action, JSONArray args, CallbackContext callbackContext) throws JSONException {
        if (action.equals("echo")) {
        	int data_num = args.getInt(0);
        	String[] id_ptr = new String[data_num];
        	double[] array_ptr = new double[data_num * 2];
        	int id_ptr_idx = 0;
        	int array_ptr_idx = 0;
        	int i = 1;
        	while(i < data_num * 3 + 1){
        		id_ptr[id_ptr_idx] = args.getString(i);
        		array_ptr[array_ptr_idx] = args.getDouble(i + 1);
        		array_ptr[array_ptr_idx + 1] = args.getDouble(i + 2);
        		
        		id_ptr_idx ++;
        		array_ptr_idx += 2;
        		i += 3;
        	}
        	
        	String[] ret = calculateSatisfiedDistance(id_ptr, id_ptr.length, array_ptr, array_ptr.length);      
        	System.out.println("ret length: " + ret.length);
        	
        	String return_val = "[";
        	for(i = 0; i < ret.length; i++){
        		return_val += (ret[i]);
        		if(i + 1 != ret.length){
        			return_val += ",";
        		}
        	}
        	return_val+="]";
            //callbackContext.success(return_val);
        	callbackContext.success(new JSONArray(return_val));
            // this.echo("Done", callbackContext);
            return true;
        }
        return false;
    }

    private void echo(String message, CallbackContext callbackContext) {
        if (message != null && message.length() > 0) {
            callbackContext.success(message);
        } else {
            callbackContext.error("Expected one non-empty string argument.");
        }
    }
}
