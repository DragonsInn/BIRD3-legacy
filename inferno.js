i//mport { render, Component } from 'inferno';
const {Component} = require("inferno");
const ssr = require("inferno-server");


class MyComponent extends Component {
  constructor(props) {
    super(props);
    this.state = {
      counter: 0
    };
  }
  render() {
    return (
      <div>
        <h1>Header!</h1>
        <span>Counter is at: { this.state.counter }</span>
      </div>
    );
  }
}
/*
ssr.renderToStaticMarkup(
  <MyComponent />,
  document.getElementById("app")
);*/
