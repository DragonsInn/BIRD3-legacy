// require.context returns something like require.
// But instead of being a normal require, it loads files from the context.
var tests = require.context('./tests', true, /.+\.test\.(php|oj|js)$/);

// We recurse the call in order to call the tests in.
tests.keys().forEach(function(testId){
    tests(testId);
});
