<div id="page-loader"
     class="fixed inset-0 flex items-center justify-center bg-[#101727] z-[9999] transition-opacity duration-700">
  <div class="tetris-container">
    <div class="block block-L block-L-90" style="--pos-x: 3; --pos-y: 1;">
      <div class="square"></div><div class="square"></div><div class="square"></div><div class="square"></div>
    </div>
    <div class="block block-O" style="--pos-x: 1; --pos-y: 1;">
      <div class="square"></div><div class="square"></div><div class="square"></div><div class="square"></div>
    </div>
    <div class="block block-Z" style="--pos-x: 2; --pos-y: 2;">
      <div class="square"></div><div class="square"></div><div class="square"></div><div class="square"></div>
    </div>
    <div class="block block-O" style="--pos-x: 4; --pos-y: 3;">
      <div class="square"></div><div class="square"></div><div class="square"></div><div class="square"></div>
    </div>
    <div class="block block-L block-L-270" style="--pos-x: 1; --pos-y: 3;">
      <div class="square"></div><div class="square"></div><div class="square"></div><div class="square"></div>
    </div>
    <div class="block block-I" style="--pos-x: 6; --pos-y: 1;">
      <div class="square"></div><div class="square"></div><div class="square"></div><div class="square"></div>
    </div>
  </div>
</div>

<style>
.tetris-container {
  --animation-duration: 10s;
  --grid-size-x: 6;
  --grid-size-y: 9;
  --square-size: 1rem;
  background: rgba(0, 0, 0, 0.2);
  border: 2px solid rgba(255, 255, 255, 0.1);
  height: calc(var(--grid-size-y) * var(--square-size));
  width: calc(var(--grid-size-x) * var(--square-size));
  border-radius: 0.5rem;
  position: relative;
  overflow: hidden;
  box-shadow: 0 0 30px rgba(0, 194, 255, 0.2);
}

.tetris-container::before {
  content: "";
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(rgba(255,255,255,0.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,0.05) 1px, transparent 1px);
  background-size: var(--square-size) var(--square-size);
  pointer-events: none;
}

.block {
  animation: fallBlock var(--animation-duration) linear infinite;
  display: grid;
  gap: 0;
  grid-template-columns: repeat(var(--grid-size-x), var(--square-size));
  grid-template-rows: repeat(var(--grid-size-y), var(--square-size));
  position: absolute;
}

.block-I { color: #00c2ff; }
.block-L { color: #ff4d4d; }
.block-O { color: #f8e71c; }
.block-Z { color: #00ff9d; }

.square {
  --shadow: inset 2px 2px 2px rgba(255,255,255,0.4),
             inset -2px -2px 2px rgba(0,0,0,0.4);
  animation: clearSquare var(--animation-duration) ease-out infinite;
  background-color: currentColor;
  box-shadow: var(--shadow);
  width: var(--square-size);
  height: var(--square-size);
}

@keyframes fallBlock {
  0% { bottom: 100%; opacity: 1; }
  90% { opacity: 1; }
  100% { bottom: 0; opacity: 0; }
}

@keyframes clearSquare {
  0%, 85% { background: currentColor; box-shadow: var(--shadow); }
  95%, 100% { background: #fff; box-shadow: none; }
}
</style>
