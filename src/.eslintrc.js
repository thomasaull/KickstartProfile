module.exports = {
  root: true,
  env: {
    node: true
  },
  parserOptions: {
    parser: 'babel-eslint',
    sourceType: 'module'
  },
  extends: ['plugin:prettier/recommended'],

  globals: {
    TimelineLite: false,
    TimelineMax: false,
    TweenLite: false,
    TweenMax: false,
    Back: false,
    Bounce: false,
    Circ: false,
    Cubic: false,
    Ease: false,
    EaseLookup: false,
    Elastic: false,
    Expo: false,
    Linear: false,
    Power0: false,
    Power1: false,
    Power2: false,
    Power3: false,
    Power3: false,
    Power4: false,
    Quad: false,
    Quart: false,
    Quint: false,
    RoughEase: false,
    Sine: false,
    SlowMo: false,
    SteppedEase: false,
    Strong: false,
    Draggable: false,
    SplitText: false,
    VelocityTracker: false,
    CSSPlugin: false,
    ThrowPropsPlugin: false,
    BezierPlugin: false
  }
}
