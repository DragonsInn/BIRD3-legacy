console.log("Hello! :D")

setTimeout(_=>{
  if(require("is-docker")()) {
    console.log("We are in docker.")
  } else {
    console.log("We're not in docker?...")
  }
}, 3000)

let counter = 0;
function keepTickingForever() {
  process.nextTick(_=>{
    counter++;
    setTimeout(keepTickingForever, 1000);
  })
}

process.on("exit", ()=>{
  console.log("Had ticks: "+counter)
})

keepTickingForever();
