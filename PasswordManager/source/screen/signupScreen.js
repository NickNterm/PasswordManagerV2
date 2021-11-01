import React from "react";
import {
  SafeAreaView,
  TextInput,
  StyleSheet,
  Text,
  TouchableWithoutFeedback,
} from "react-native";
import AsyncStorage from "@react-native-async-storage/async-storage";
import colors from "../style/colors";
import ButtonFilled from "../widgets/buttonFilled";
import { databaseSignUp } from "../database/database";

function signupScreen({ navigation }) {
  // Setup React native useState Hooks
  const [username, onUsernameChange] = React.useState("");
  const [password, onPasswordChange] = React.useState("");
  const [repeat, onRepeatChange] = React.useState("");
  const [error, setError] = React.useState("");
  const [loading, setLoading] = React.useState(false);

  // Gets called when the user is successfully registered
  // It saves the given token to the device so that the user
  // do not have to reconnect
  const signUpCallback = async (value) => {
    try {
      await AsyncStorage.setItem("token", jsonValue);
    } catch (e) {
      setError(e.toString());
    }
    // go to the next main page the token in saved
    navigation.replace("Home");
    setLoading(false);
  };
  function showError(error) {
    setError(error);
    setLoading(false);
  }

  // Checks if the passwords are the same and then it is creating the api request
  function signUp() {
    if (password == repeat) {
      setLoading(true);
      databaseSignUp(username, password, signUpCallback, showError);
    } else {
      setError("Passwords do not match");
      setLoading(false);
    }
  }

  // UI elements
  return (
    <SafeAreaView
      style={{ flex: 1, alignItems: "center", backgroundColor: "#fff" }}
    >
      <TextInput
        style={[styles.input, { marginTop: 10 }]}
        onChangeText={onUsernameChange}
        placeholder={"Username"}
        value={username}
      />
      <TextInput
        style={styles.input}
        onChangeText={onPasswordChange}
        secureTextEntry={true}
        placeholder={"Password"}
        value={password}
      />
      <TextInput
        style={styles.input}
        onChangeText={onRepeatChange}
        secureTextEntry={true}
        placeholder={"Repeat"}
        value={repeat}
      />
      {error != "" ? (
        <Text
          style={{
            width: "90%",
            textAlign: "center",
            textAlignVertical: "center",
            height: 40,
            color: "#fff",
            backgroundColor: colors.error_red,
            borderRadius: 5,
            marginTop: 5,
          }}
        >
          {error}
        </Text>
      ) : null}
      <ButtonFilled
        text={"Sign Up"}
        onClick={() => signUp()}
        style={{ marginTop: 10 }}
        loading={loading}
      />
      <Text style={{ marginTop: 10, fontSize: 15 }}>
        You already have and account?{" "}
        {
          <TouchableWithoutFeedback
            onPress={() => {
              navigation.navigate("Sign In");
            }}
          >
            <Text style={{ color: colors.primary_color, fontWeight: "bold" }}>
              Sign In
            </Text>
          </TouchableWithoutFeedback>
        }
      </Text>
    </SafeAreaView>
  );
}

// Styles
const styles = StyleSheet.create({
  input: {
    backgroundColor: colors.grey_input,
    borderRadius: 5,
    padding: 10,
    height: 40,
    width: "90%",
    marginTop: 5,
  },
});

export default signupScreen;
