import React from "react";
import { TouchableHighlight, Text, ActivityIndicator } from "react-native";
import colors from "../style/colors";
function FilledButton(props) {
  var loading = props.loading;
  return (
    <TouchableHighlight
      style={[
        props.style,
        {
          backgroundColor: colors.primary_color,
          width: "90%",
          alignItems: "center",
          height: 40,
          justifyContent: "center",
          borderRadius: 5,
        },
      ]}
      underlayColor={colors.filled_button_underlay}
      onPress={() => {
        loading != null && loading ? null : props.onClick();
      }}
    >
      {loading != null && loading ? (
        <ActivityIndicator size={22} color="#fff" />
      ) : (
        <Text style={{ color: "#fff", textTransform: "uppercase" }}>
          {props.text}
        </Text>
      )}
    </TouchableHighlight>
  );
}
export default FilledButton;
