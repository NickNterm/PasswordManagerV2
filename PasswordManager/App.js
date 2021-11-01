import React from "react";
import { NavigationContainer } from "@react-navigation/native";
import { createStackNavigator } from "@react-navigation/stack";
import { TransitionPresets } from "@react-navigation/stack";
import loginScreen from "./source/screen/loginScreen";
import signupScreen from "./source/screen/signupScreen";
import colors from "./source/style/colors";
import mainScreen from "./source/screen/mainScreen";
import splashScreen from "./source/screen/splashScreen";

const Stack = createStackNavigator();

export default function App() {
  return (
    <NavigationContainer>
      <Stack.Navigator>
        <Stack.Screen
          name="Splash"
          component={splashScreen}
          options={{ headerShown: false }}
        />
        <Stack.Screen
          name="Sign Up"
          component={signupScreen}
          options={{
            ...TransitionPresets.SlideFromRightIOS,
            headerTintColor: "#fff",
            headerStyle: {
              backgroundColor: colors.primary_color,
            },
          }}
        />
        <Stack.Screen
          name="Sign In"
          component={loginScreen}
          options={{
            ...TransitionPresets.SlideFromRightIOS,
            headerTintColor: "#fff",
            headerStyle: {
              backgroundColor: colors.primary_color,
            },
          }}
        />
        <Stack.Screen
          name="Home"
          component={mainScreen}
          options={{
            ...TransitionPresets.SlideFromRightIOS,
            headerTintColor: "#fff",
            headerStyle: {
              backgroundColor: colors.primary_color,
            },
          }}
        />
      </Stack.Navigator>
    </NavigationContainer>
  );
}
