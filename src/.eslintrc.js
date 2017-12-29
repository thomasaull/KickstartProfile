module.exports = {
    root: true,
    parser: 'babel-eslint',
    parserOptions: {
      sourceType: 'module'
    },
    env: {
      browser: true,
    },
    extends: 'standard',
    plugins: [
      'html'
    ],
    rules: {
    // "no-new": 0,
    "space-before-function-paren": ["error", {
      "anonymous": "ignore",
      "named": "ignore",
      "asyncArrow": "ignore"
    }],
    // allow paren-less arrow functions
    'arrow-parens': 0,
    // allow async-await
    'generator-star-spacing': 0,
    // allow debugger during development
    'no-debugger': process.env.NODE_ENV === 'production' ? 2 : 0
  },
  globals: {
    "TimelineLite" : false,
    "TimelineMax" : false,
    "TweenLite" : false,
    "TweenMax" : false,
    "Back" : false,
    "Bounce" : false,
    "Circ" : false,
    "Cubic" : false,
    "Ease" : false,
    "EaseLookup" : false,
    "Elastic" : false,
    "Expo" : false,
    "Linear" : false,
    "Power0" : false,
    "Power1" : false,
    "Power2" : false,
    "Power3" : false,
    "Power3" : false,
    "Power4" : false,
    "Quad" : false,
    "Quart" : false,
    "Quint" : false,
    "RoughEase" : false,
    "Sine" : false,
    "SlowMo" : false,
    "SteppedEase" : false,
    "Strong" : false,
    "Draggable" : false,
    "SplitText" : false,
    "VelocityTracker" : false, 
    "CSSPlugin" : false,
    "ThrowPropsPlugin" : false, 
    "BezierPlugin" : false
  }
};