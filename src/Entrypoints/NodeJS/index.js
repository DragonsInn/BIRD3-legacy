console.log("Hello! :D")

if(require("is-docker")()) {
  console.log("We are in docker.")
} else {
  console.log("We're not in docker?...")
}
