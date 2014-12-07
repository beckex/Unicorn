LOCAL_PATH := $(call my-dir)

include $(CLEAR_VARS)

LOCAL_MODULE    := calculateDistance
LOCAL_SRC_FILES := calculateDistance.c

include $(BUILD_SHARED_LIBRARY)
