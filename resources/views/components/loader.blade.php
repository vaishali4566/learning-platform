<style>
  #page-loader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #101727;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
  }

  .loader {
    position: relative;
    width: 108px;
    display: flex;
    justify-content: space-between;
  }

  .loader::after,
  .loader::before {
    content: "";
    display: inline-block;
    width: 48px;
    height: 48px;
    background-color: #fff;
    background-image: radial-gradient(circle 14px, #0d161b 100%, transparent 0);
    background-repeat: no-repeat;
    border-radius: 50%;
    animation: eyeMove 10s infinite, blink 10s infinite;
  }

  @keyframes eyeMove {
    0%, 10% { background-position: 0px 0px; }
    13%, 40% { background-position: -15px 0px; }
    43%, 70% { background-position: 15px 0px; }
    73%, 90% { background-position: 0px 15px; }
    93%, 100% { background-position: 0px 0px; }
  }

  @keyframes blink {
    0%, 10%, 12%, 20%, 22%, 40%, 42%, 60%, 62%, 70%, 72%, 90%, 92%, 98%, 100% {
      height: 48px;
    }
    11%, 21%, 41%, 61%, 71%, 91%, 99% {
      height: 18px;
    }
  }
</style>

<div id="page-loader">
  <span class="loader"></span>
</div>

<script>
  window.addEventListener('load', () => {
    const loader = document.getElementById('page-loader');
    if (loader) {
      loader.style.transition = 'opacity 0.5s';
      loader.style.opacity = '0';
      setTimeout(() => loader.remove(), 500);
    }
  });
</script>
