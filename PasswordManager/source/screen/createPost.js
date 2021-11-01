import React, { useState } from "react";
import { View, TextInput, StyleSheet, SafeAreaView, Text } from "react-native";
import NativeColorPicker from "native-color-picker";
import ButtonFilled from "../widgets/buttonFilled";
import colors from "../style/colors";

function createPost({ navigation }) {
  const [loading, setLoading] = useState(false);
  const [error, setError] = React.useState("");
  const [selectedColor, setSelectedColor] = useState("");
  const [platform, onPlatformNameChange] = useState("");
  const [username, onUsernameChange] = useState("");
  const [password, onPasswordChange] = useState("");
  const [repeat, onRepeatChange] = useState("");
  const [moreInfo, onMoreInfoChange] = useState("");
  const [hint, onHintChange] = useState("");
  function SubmitCallback() {
    // go to the next main page the account in saved
    setLoading(false);
    navigation.replace("Home");
  }

  // Show the error and stop showing the loading
  function showError(error) {
    setError(error);
    setLoading(false);
  }

  // This function is done in the Submit Button Press
  function SubmitAccount() {
    // We give the mandatory credentials to the db
    // In case there is something wrong with the credentials
    // we use the setError to display it
    // If the Account has been saved successfully then the go back to the home page.
    if (password == repeat && password.length > 0) {
      setLoading(true);
      databaseSubmitAccount(
        platform,
        username,
        password,
        moreInfo,
        hint,
        selectedColor,
        SubmitCallback,
        showError
      );
    } else {
      showError("Passwords are Invalid!");
    }
  }
  return (
    <SafeAreaView
      style={{ flex: 1, alignItems: "center", backgroundColor: "#fff" }}
    >
      <TextInput
        style={[styles.input, { marginTop: 10 }]}
        onChangeText={onPlatformNameChange}
        placeholder={"Platform"}
        value={platform}
      />
      <TextInput
        style={[styles.input, { marginTop: 10 }]}
        onChangeText={onUsernameChange}
        placeholder={"Username"}
        value={username}
      />
      <TextInput
        style={[styles.input, { marginTop: 10 }]}
        onChangeText={onPasswordChange}
        placeholder={"Password"}
        secureTextEntry={true}
        value={password}
      />
      <TextInput
        style={[styles.input, { marginTop: 10 }]}
        onChangeText={onRepeatChange}
        placeholder={"Repeat"}
        secureTextEntry={true}
        value={repeat}
      />
      <TextInput
        style={[styles.input, { marginTop: 10 }]}
        onChangeText={onMoreInfoChange}
        placeholder={"More Info"}
        value={moreInfo}
      />
      <TextInput
        style={[styles.input, { marginTop: 10, marginBottom: 10 }]}
        onChangeText={onHintChange}
        placeholder={"Hint"}
        value={hint}
      />
      <Text style={{ textAlign: "left", width: "90%", color: "#575757" }}>
        Color
      </Text>
      <View
        style={{
          borderColor: "#757575",
          borderRadius: 5,
          borderWidth: 1,
          width: "90%",
        }}
      >
        <NativeColorPicker
          itemSize={30}
          columns={5}
          style={{ alignSelf: "center" }}
          onSelect={(color) => {
            setSelectedColor(color);
            navigation.setOptions({
              headerStyle: {
                backgroundColor: color,
              },
            });
          }}
          colors={[
            "#FF595E",
            "#FF924C",
            "#FFCA3A",
            "#C5CA30",
            "#8AC926",
            "#52A675",
            "#1982C4",
            "#4267AC",
            "#6A4C93",
            "#A656A7",
          ]}
        />
      </View>
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
            marginTop: 10,
          }}
        >
          {error}
        </Text>
      ) : null}
      <ButtonFilled
        text={"Submit"}
        onClick={() => SubmitAccount()}
        backgroundColor={selectedColor}
        style={{ marginTop: 10 }}
        loading={loading}
      />
    </SafeAreaView>
  );
}
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

export default createPost;
