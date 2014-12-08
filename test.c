/*
 * Copyright (C) 2009 The Android Open Source Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */
#include <string.h>
#include <jni.h>
#include <math.h>
#include <stdlib.h>
#include <pthread.h>

#define M_PI 3.14159265358979323846
#define THREAD_NUMBER 4
#define DISTANCE 100

const char ** pass_by_user_array;
int pass_by_user_num;
const char ** id_array;
double * location_array;
double lon0;
double lat0;


typedef struct _threadIn{
	pthread_mutex_t *m;
	int start;
	int end;
}threadIn;

double calculateDistanceBetweenTwoLocation(double lon1, double lat1, double lon2, double lat2){
	double R = 6371 * 1000; // meters
	double phi1 = lat1 / 180 * M_PI;
	double phi2 = lat2 / 180 * M_PI;
	double delta_phi = (lat2 - lat1) / 180 * M_PI;
	double delta_lambda = (lon2 - lon1) / 180 * M_PI;

	double a = sin(delta_phi / 2) * sin(delta_phi / 2) +
	                cos(phi1) * cos(phi2) *
	                sin(delta_lambda / 2) * sin(delta_lambda / 2);
	double c = 2 * atan2(sqrt(a), sqrt(1 - a));

	double d = R * c;
	return d;
}


void *findUserThread(void *in){
	threadIn *dataIn=(threadIn*)in;
	int i=0;
	for(i=dataIn->start;i<dataIn->end;i++){
		double lon = location_array[2*i];
		double lat = location_array[2*i+1];
		double distance = calculateDistanceBetweenTwoLocation(lon0, lat0, lon, lat);
		if(distance<DISTANCE){
			pthread_mutex_lock(dataIn->m);
			pass_by_user_array[pass_by_user_num] = id_array[i]; // save valid pass by user id to that array
			pass_by_user_num++;
			pthread_mutex_unlock(dataIn->m);
		}

	}
	return NULL;
}
/* This is a trivial JNI example where we use a native method
 * to return a new VM String. See the corresponding Java source
 * file located at:
 *
 *   apps/samples/hello-jni/project/src/com/example/hellojni/HelloJni.java
 */
/*
jstring
Java_com_shd101wyy_plugin_Echo_stringFromJNI( JNIEnv* env,
                                                  jobject thiz,
                                                  jstring username,
                                                  jstring password)
{
#if defined(__arm__)
  #if defined(__ARM_ARCH_7A__)
    #if defined(__ARM_NEON__)
      #if defined(__ARM_PCS_VFP)
        #define ABI "armeabi-v7a/NEON (hard-float)"
      #else
        #define ABI "armeabi-v7a/NEON"
      #endif
    #else
      #if defined(__ARM_PCS_VFP)
        #define ABI "armeabi-v7a (hard-float)"
      #else
        #define ABI "armeabi-v7a"
      #endif
    #endif
  #else
   #define ABI "armeabi"
  #endif
#elif defined(__i386__)
   #define ABI "x86"
#elif defined(__x86_64__)
   #define ABI "x86_64"
#elif defined(__mips64)  // mips64el-* toolchain defines __mips__ too
   #define ABI "mips64"
#elif defined(__mips__)
   #define ABI "mips"
#elif defined(__aarch64__)
   #define ABI "arm64-v8a"
#else
   #define ABI "unknown"
#endif

	const char * password_ = (*env)->GetStringUTFChars(env, password, (jboolean*)0);

    return (*env)->NewStringUTF(env, password_);
}
*/

jobjectArray
Java_com_shd101wyy_plugin_Echo_calculateSatisfiedDistance( JNIEnv* env,
                                                          jobject thiz,
                                                          jobjectArray id_ptr,     // array of user id
                                                          jint id_length,          // the length of above array
                                                          jdoubleArray array_ptr,  // array of lon and lat
                                                          jint array_length)       // length of that array
{
#if defined(__arm__)
  #if defined(__ARM_ARCH_7A__)
    #if defined(__ARM_NEON__)
      #if defined(__ARM_PCS_VFP)
        #define ABI "armeabi-v7a/NEON (hard-float)"
      #else
        #define ABI "armeabi-v7a/NEON"
      #endif
    #else
      #if defined(__ARM_PCS_VFP)
        #define ABI "armeabi-v7a (hard-float)"
      #else
        #define ABI "armeabi-v7a"
      #endif
    #endif
  #else
   #define ABI "armeabi"
  #endif
#elif defined(__i386__)
   #define ABI "x86"
#elif defined(__x86_64__)
   #define ABI "x86_64"
#elif defined(__mips64)  /* mips64el-* toolchain defines __mips__ too */
   #define ABI "mips64"
#elif defined(__mips__)
   #define ABI "mips"
#elif defined(__aarch64__)
   #define ABI "arm64-v8a"
#else
   #define ABI "unknown"
#endif

	/*
	 * id_array is in foramt of user_id0 user_id1 user_id2
	 *  where user_id0 is current user
	 *
	 */
	id_array = (const char**)malloc(sizeof(char*) * id_length); // create array to stores all ids
	int i = 0;
	for(i = 0; i < id_length; i++){
		jstring string = (jstring)(*env)->GetObjectArrayElement(env, id_ptr, i);
		const char * rawString = (*env)->GetStringUTFChars(env, string, 0);
		id_array[i] = rawString;
	}

	/*
	 * array_ptr is in format of lon0 lat0 lon1 lat1 lon2 lat2
	 * where lon0 lat0 is the longitude and latitude of current user
	 *
	 */
	location_array = (*env)->GetDoubleArrayElements(env, array_ptr, 0);
	lon0 = location_array[0];
	lat0 = location_array[1];

	pass_by_user_num = 0;
	pass_by_user_array = (const char**)malloc(sizeof(char*) * (id_length));

	/*
	 * You guys need to change the code below to satisfy synchronization and implement multi-thread
	 */
	// I write a async version first.
	threadIn *pass_to_thread=(threadIn*)malloc(sizeof(threadIn)*THREAD_NUMBER);
	int interval=(id_length-2)/THREAD_NUMBER;
	pthread_mutex_t thread_mutex;
	pthread_mutex_init(&thread_mutex, NULL);
	pthread_t pid[THREAD_NUMBER];
	for(i=0;i<THREAD_NUMBER;i++){
		pass_to_thread[i].m=&thread_mutex;
		pass_to_thread[i].start=2+i*interval;
		if(i==THREAD_NUMBER-1)
			pass_to_thread[i].end=id_length;
		else
			pass_to_thread[i].end=2+(i+1)*interval;
		pthread_create(pid+i, NULL, findUserThread, pass_to_thread+i);
	}
	for(i=0;i<THREAD_NUMBER;i++){
		pthread_join(pid[i], NULL);
	}
	pthread_mutex_destroy(&thread_mutex);
	free(pass_to_thread);

	// make return value array
	jobjectArray ret;
	ret = (jobjectArray)(*env)->NewObjectArray(env, pass_by_user_num, (*env)->FindClass(env, "java/lang/String"), (*env)->NewStringUTF(env, ""));
	for(i = 0; i < pass_by_user_num; i++){
		(*env)->SetObjectArrayElement(env, ret, i, (*env)->NewStringUTF(env, pass_by_user_array[i]));
	}
	return ret;
}
