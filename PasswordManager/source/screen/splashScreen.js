import React, { useEffect } from "react";
import { View } from "react-native";
import AsyncStorage from "@react-native-async-storage/async-storage";
function splashScreen({ navigation }) {
  useEffect(() => {
    async function getData() {
      try {
        const value = await AsyncStorage.getItem("token");
        if (value !== null) {
          console.log(value);
          navigation.replace("Home");
        } else {
          navigation.replace("SignUp");
        }
      } catch (e) {}
    }
    getData();
  }, []);
  return <View></View>;
}

export default splashScreen;
