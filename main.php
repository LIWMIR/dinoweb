<?php
// Dino Adventure - Web Edition
// Assets are loaded from the same directory
$assets = ['dino.png','dino1.png','boss.png','coin.png','kasktus1.png','meteor.png','door.png','nebo.png','fondino.png'];
?>
<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>🦖 Dino Adventure – Parkur Edition</title>
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

:root {
  --purple: #7c3aff;
  --cyan:   #00d4ff;
  --pink:   #ff2d78;
  --dark:   #04010f;
  --card:   rgba(10,5,30,0.55);
  --border: rgba(124,58,255,0.35);
  --glow:   0 0 30px rgba(124,58,255,0.5);
}

html, body {
  width:100%; height:100%;
  background: var(--dark);
  color:#e0e7ff;
  font-family:'Rajdhani',sans-serif;
  overflow:hidden;
}

/* ─── STARFIELD ─────────────────────────────────────────── */
#stars-bg {
  position:fixed; inset:0; z-index:0; pointer-events:none;
}

/* ─── LAYOUT ─────────────────────────────────────────────── */
#app {
  position:relative; z-index:1;
  width:100vw; height:100vh;
  display:flex; flex-direction:column;
  align-items:center; justify-content:center;
  gap:18px;
}

/* ─── TITLE BAR ───────────────────────────────────────────── */
#title-bar {
  text-align:center;
  animation: floatY 4s ease-in-out infinite;
}
#title-bar h1 {
  font-family:'Orbitron',sans-serif;
  font-weight:900;
  font-size:clamp(1.4rem,3.5vw,2.4rem);
  letter-spacing:4px;
  background: linear-gradient(135deg, var(--purple), var(--cyan), var(--pink));
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
  background-clip:text;
  filter: drop-shadow(0 0 12px rgba(0,212,255,0.5));
}
#title-bar p {
  font-size:.85rem; letter-spacing:3px; color:rgba(160,140,255,0.7);
  margin-top:4px; text-transform:uppercase;
}

/* ─── CANVAS WRAPPER ──────────────────────────────────────── */
#canvas-wrap {
  position:relative;
  border-radius:16px;
  border:1px solid var(--border);
  box-shadow: var(--glow), inset 0 0 60px rgba(0,212,255,0.03);
  overflow:hidden;
  background:#000;
}
#canvas-wrap::before {
  content:'';
  position:absolute; inset:0;
  border-radius:16px;
  pointer-events:none;
  box-shadow: inset 0 0 0 1px rgba(0,212,255,0.12);
  z-index:10;
}
canvas {
  display:block;
  image-rendering:pixelated;
}

/* ─── HUD BAR ─────────────────────────────────────────────── */
#hud {
  display:flex; gap:24px; align-items:center;
  background: var(--card);
  backdrop-filter:blur(20px);
  border:1px solid var(--border);
  border-radius:50px;
  padding:10px 28px;
  box-shadow: var(--glow);
}
.hud-item {
  display:flex; align-items:center; gap:8px;
  font-family:'Orbitron',sans-serif; font-size:.85rem;
  letter-spacing:1px;
}
.hud-item span.label { color:rgba(160,140,255,0.6); font-size:.7rem; }
.hud-item span.val   { color:var(--cyan); font-weight:700; }
.hud-sep { width:1px; height:20px; background:var(--border); }

/* ─── CONTROLS BAR ───────────────────────────────────────── */
#controls-bar {
  display:flex; gap:12px; align-items:center;
  flex-wrap:wrap; justify-content:center;
}
.ctrl-btn {
  font-family:'Orbitron',sans-serif;
  font-size:.7rem; letter-spacing:2px;
  padding:9px 20px;
  border-radius:50px;
  border:1px solid var(--border);
  background: var(--card);
  color:var(--cyan);
  cursor:pointer;
  transition:all .25s;
  backdrop-filter:blur(10px);
}
.ctrl-btn:hover {
  background:rgba(0,212,255,0.12);
  border-color:var(--cyan);
  box-shadow:0 0 20px rgba(0,212,255,0.35);
  transform:translateY(-2px);
}
.ctrl-btn.danger { color:var(--pink); border-color:rgba(255,45,120,0.35); }
.ctrl-btn.danger:hover { background:rgba(255,45,120,0.12); border-color:var(--pink); box-shadow:0 0 20px rgba(255,45,120,0.35); }
.ctrl-btn.active { background:rgba(124,58,255,0.2); border-color:var(--purple); }

/* key hints */
#key-hints {
  font-size:.72rem; color:rgba(160,140,255,0.5);
  letter-spacing:1px; text-align:center;
}
kbd {
  background:rgba(124,58,255,0.2);
  border:1px solid var(--border);
  border-radius:4px;
  padding:1px 6px;
  font-family:'Orbitron',sans-serif;
  font-size:.65rem;
  color:var(--cyan);
}

/* ─── MENU OVERLAY ───────────────────────────────────────── */
#menu-overlay {
  position:absolute; inset:0;
  z-index:20;
  display:flex; flex-direction:column;
  align-items:center; justify-content:center;
  gap:24px;
  background:rgba(4,1,15,0.82);
  backdrop-filter:blur(6px);
  border-radius:16px;
  transition:opacity .4s;
}
#menu-overlay.hidden { opacity:0; pointer-events:none; }

#menu-title {
  font-family:'Orbitron',sans-serif;
  font-weight:900; font-size:clamp(1.6rem,4vw,2.8rem);
  letter-spacing:5px;
  background:linear-gradient(135deg,var(--purple),var(--cyan));
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
  background-clip:text;
  filter:drop-shadow(0 0 18px rgba(0,212,255,0.5));
  animation:pulse-glow 2s ease-in-out infinite;
}

.menu-subtitle {
  font-size:.8rem; letter-spacing:3px;
  color:rgba(160,140,255,0.5);
  text-transform:uppercase;
}

#level-btns {
  display:flex; gap:12px; flex-wrap:wrap; justify-content:center;
}
.lvl-btn {
  width:70px; height:70px;
  border-radius:12px;
  border:1px solid var(--border);
  background:rgba(124,58,255,0.1);
  color:#e0e7ff;
  font-family:'Orbitron',sans-serif;
  font-size:.75rem; font-weight:700;
  cursor:pointer;
  transition:all .2s;
  display:flex; flex-direction:column;
  align-items:center; justify-content:center;
  gap:4px;
  letter-spacing:1px;
}
.lvl-btn .num { font-size:1.4rem; color:var(--cyan); }
.lvl-btn:hover {
  background:rgba(0,212,255,0.15);
  border-color:var(--cyan);
  box-shadow:0 0 25px rgba(0,212,255,0.4);
  transform:translateY(-4px) scale(1.05);
}

#music-toggle-menu {
  font-family:'Orbitron',sans-serif;
  font-size:.7rem; letter-spacing:2px;
  padding:10px 26px;
  border-radius:50px;
  border:1px solid rgba(255,45,120,0.4);
  background:rgba(255,45,120,0.08);
  color:var(--pink);
  cursor:pointer;
  transition:all .25s;
}
#music-toggle-menu:hover {
  background:rgba(255,45,120,0.18);
  box-shadow:0 0 20px rgba(255,45,120,0.4);
}

/* ─── END OVERLAY ────────────────────────────────────────── */
#end-overlay {
  position:absolute; inset:0; z-index:20;
  display:none; flex-direction:column;
  align-items:center; justify-content:center;
  gap:20px;
  border-radius:16px;
  transition:opacity .4s;
}
#end-overlay.show { display:flex; }
#end-title {
  font-family:'Orbitron',sans-serif;
  font-weight:900; font-size:clamp(2rem,5vw,3.5rem);
  letter-spacing:6px;
}
#end-score {
  font-family:'Orbitron',sans-serif;
  font-size:1rem; letter-spacing:3px;
  color:var(--cyan);
}
.end-btns { display:flex; gap:14px; }

/* ─── SCANLINE overlay ───────────────────────────────────── */
#scanlines {
  position:absolute; inset:0; z-index:15; pointer-events:none;
  background: repeating-linear-gradient(
    0deg, transparent, transparent 2px,
    rgba(0,0,0,0.07) 2px, rgba(0,0,0,0.07) 4px
  );
  border-radius:16px;
}

/* ─── ANIMATIONS ─────────────────────────────────────────── */
@keyframes floatY   { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
@keyframes pulse-glow { 0%,100%{filter:drop-shadow(0 0 18px rgba(0,212,255,0.5))} 50%{filter:drop-shadow(0 0 30px rgba(0,212,255,0.9))} }
@keyframes fadeIn   { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }

/* ─── SCANLINE corner deco ───────────────────────────────── */
#canvas-wrap::after {
  content:'';
  position:absolute;
  top:0; left:0; width:100%; height:3px;
  background:linear-gradient(90deg,transparent,var(--cyan),transparent);
  z-index:11;
  animation:scanBar 3s linear infinite;
  opacity:.5;
}
@keyframes scanBar {
  0%  {transform:translateY(0)}
  100%{transform:translateY(400px)}
}
</style>
</head>
<body>

<!-- Stars canvas -->
<canvas id="stars-bg"></canvas>

<div id="app">
  <div id="title-bar">
    <h1>⚡ DINO ADVENTURE</h1>
    <p>Parkur Edition — Web Build</p>
  </div>

  <!-- Game canvas -->
  <div id="canvas-wrap">
    <canvas id="game" width="800" height="400"></canvas>
    <div id="scanlines"></div>

    <!-- Menu overlay -->
    <div id="menu-overlay">
      <div id="menu-title">DINO ADVENTURE</div>
      <div class="menu-subtitle">// Vyber úroveň //</div>
      <div id="level-btns">
        <button class="lvl-btn" data-lvl="1"><span class="num">1</span>LEVEL</button>
        <button class="lvl-btn" data-lvl="2"><span class="num">2</span>LEVEL</button>
        <button class="lvl-btn" data-lvl="3"><span class="num">3</span>LEVEL</button>
        <button class="lvl-btn" data-lvl="4"><span class="num">4</span>BOSS</button>
        <button class="lvl-btn" data-lvl="5"><span class="num">🚂</span>TRAIN</button>
      </div>
      <button id="music-toggle-menu">🔇 Vypnout hudbu</button>
    </div>

    <!-- End overlay -->
    <div id="end-overlay">
      <div id="end-title"></div>
      <div id="end-score"></div>
      <div class="end-btns">
        <button class="ctrl-btn" id="btn-restart">↺ RESTART</button>
        <button class="ctrl-btn" id="btn-menu">⌂ MENU</button>
      </div>
    </div>
  </div>

  <div id="hud">
    <div class="hud-item">
      <span class="label">SCORE</span>
      <span class="val" id="hud-score">0</span>
    </div>
    <div class="hud-sep"></div>
    <div class="hud-item">
      <span class="label">LEVEL</span>
      <span class="val" id="hud-level">—</span>
    </div>
    <div class="hud-sep"></div>
    <div class="hud-item">
      <span class="label">BOSS HP</span>
      <span class="val" id="hud-boss">—</span>
    </div>
  </div>

  <div id="controls-bar">
    <button class="ctrl-btn active" id="btn-music">🎵 Hudba: ON</button>
    <button class="ctrl-btn" id="btn-show-menu">⌂ Menu</button>
  </div>

  <div id="key-hints">
    <kbd>A</kbd><kbd>D</kbd> pohyb &nbsp;|&nbsp; <kbd>SPACE</kbd> skok &nbsp;|&nbsp;
    game over: <kbd>X</kbd> restart &nbsp; <kbd>M</kbd> menu
  </div>
</div>

<!-- Audio -->
<audio id="audio-game"  src="gamesong.mp3" loop></audio>
<audio id="audio-boss"  src="boss.mp3"     loop></audio>
<audio id="audio-coin"  src="coinsi.mp3"></audio>

<script>
/* ═══════════════════════════════════════════════════════════
   STARFIELD BACKGROUND
═══════════════════════════════════════════════════════════ */
(function(){
  const c=document.getElementById('stars-bg');
  const ctx=c.getContext('2d');
  let stars=[];
  function resize(){c.width=window.innerWidth;c.height=window.innerHeight;}
  function init(){
    stars=[];
    for(let i=0;i<260;i++){
      stars.push({x:Math.random()*c.width,y:Math.random()*c.height,
        r:Math.random()*1.8+.2,a:Math.random(),da:(Math.random()-.5)*.008,
        speed:Math.random()*.15+.05});
    }
  }
  function draw(){
    ctx.clearRect(0,0,c.width,c.height);
    // nebula blobs
    const nb=[
      {x:c.width*.2,y:c.height*.3,r:220,c:'rgba(124,58,255,0.04)'},
      {x:c.width*.8,y:c.height*.6,r:180,c:'rgba(0,212,255,0.04)'},
      {x:c.width*.5,y:c.height*.8,r:260,c:'rgba(255,45,120,0.03)'},
    ];
    nb.forEach(n=>{
      const g=ctx.createRadialGradient(n.x,n.y,0,n.x,n.y,n.r);
      g.addColorStop(0,n.c); g.addColorStop(1,'transparent');
      ctx.fillStyle=g; ctx.fillRect(0,0,c.width,c.height);
    });
    stars.forEach(s=>{
      s.a+=s.da; if(s.a<0||s.a>1)s.da*=-1;
      s.y+=s.speed; if(s.y>c.height)s.y=0;
      ctx.globalAlpha=s.a;
      ctx.fillStyle='#fff';
      ctx.beginPath();ctx.arc(s.x,s.y,s.r,0,Math.PI*2);ctx.fill();
    });
    ctx.globalAlpha=1;
    requestAnimationFrame(draw);
  }
  resize(); init(); draw();
  window.addEventListener('resize',()=>{resize();init();});
})();

/* ═══════════════════════════════════════════════════════════
GAME ENGINE
═══════════════════════════════════════════════════════════ */
const canvas = document.getElementById('game');
const ctx    = canvas.getContext('2d');
const W=800, H=400;

// ── Audio ──────────────────────────────────────────────────
const audioGame = document.getElementById('audio-game');
const audioBoss = document.getElementById('audio-boss');
const audioCoin = document.getElementById('audio-coin');
audioGame.volume=0.35; audioBoss.volume=0.35;
let musicOn=true, currentTrack=null;

function playMusic(track){
  if(!musicOn){audioGame.pause();audioBoss.pause();return;}
  if(currentTrack===track)return;
  audioGame.pause(); audioBoss.pause();
  currentTrack=track;
  if(track==='boss'){audioBoss.currentTime=0;audioBoss.play().catch(()=>{});}
  else {audioGame.currentTime=0;audioGame.play().catch(()=>{});}
}
function stopMusic(){audioGame.pause();audioBoss.pause();currentTrack=null;}

function toggleMusic(){
  musicOn=!musicOn;
  document.getElementById('btn-music').textContent=musicOn?'🎵 Hudba: ON':'🔇 Hudba: OFF';
  document.getElementById('music-toggle-menu').textContent=musicOn?'🔇 Vypnout hudbu':'🎵 Zapnout hudbu';
  if(!musicOn)stopMusic();
}

// ── Asset loading ──────────────────────────────────────────
const imgs={};
const ASSET_LIST=['dino.png','dino1.png','boss.png','coin.png',
                  'kasktus1.png','meteor.png','door.png','nebo.png','fondino.png'];
let loaded=0;
ASSET_LIST.forEach(name=>{
  const img=new Image();
  img.onload=()=>{loaded++; if(loaded===ASSET_LIST.length)console.log('Assets OK');};
  img.onerror=()=>{loaded++;};
  img.src=name;
  imgs[name]=img;
});

function drawImg(name,x,y,w,h,fallback='#f0f'){
  if(imgs[name] && imgs[name].complete && imgs[name].naturalWidth>0){
    ctx.drawImage(imgs[name],x,y,w,h);
  } else {
    ctx.fillStyle=fallback;
    ctx.fillRect(x,y,w,h);
  }
}

// ── Game state ─────────────────────────────────────────────
let state='menu'; // menu | playing | over | win
let level=1, score=0;

// Player
const player={x:50,y:300,w:40,h:50};
const SPEED=3, JUMP=-15, GRAV=0.8;
let velY=0, onGround=false, facingLeft=false;

// Objects
let ground, platforms, enemies, enemySpeeds, coins, meteors;
let door={x:750,y:300,w:40,h:60};
let boss={x:200,y:200,w:120,h:120};
let bossHealth=10, bossSpeed=2;

// Keys
const keys={};
document.addEventListener('keydown',e=>{
  keys[e.code]=true;
  if(state==='over'||state==='win'){
    if(e.code==='KeyX') resetGame();
    if(e.code==='KeyM') goMenu();
  }
});
document.addEventListener('keyup',e=>keys[e.code]=false);

function rnd(a,b){return Math.random()*(b-a)+a;}
function rectsCollide(a,b){
  return a.x<b.x+b.w && a.x+a.w>b.x &&
         a.y<b.y+b.h && a.y+a.h>b.y;
}

// ── Load level ─────────────────────────────────────────────
function loadLevel(lvl){
  ground={x:0,y:350,w:W,h:80};
  platforms=[]; enemies=[]; enemySpeeds=[]; coins=[]; meteors=[];
  facingLeft=false; velY=0;
  player.x=50; player.y=250;

  if(lvl===1){
    platforms=[{x:200,y:270,w:150,h:20},{x:450,y:200,w:150,h:20}];
    enemies=[{x:500,y:300,w:40,h:50}]; enemySpeeds=[-2];
    coins=[{x:250,y:220,w:30,h:30},{x:500,y:150,w:30,h:30}];
    door.x=730; door.y=270;
  } else if(lvl===2){
    ground.w=200;
    platforms=[{x:250,y:280,w:100,h:20},{x:400,y:220,w:100,h:20},
               {x:550,y:280,w:100,h:20},{x:700,y:350,w:100,h:80}];
    enemies=[{x:550,y:240,w:40,h:50}]; enemySpeeds=[2];
    coins=[{x:300,y:230,w:30,h:30},{x:450,y:170,w:30,h:30}];
    door.x=730; door.y=270;
  } else if(lvl===3){
    ground.w=150;
    platforms=[{x:200,y:300,w:80,h:20},{x:350,y:250,w:80,h:20},
               {x:500,y:200,w:80,h:20},{x:700,y:350,w:100,h:80}];
    enemies=[{x:250,y:270,w:40,h:50},{x:550,y:170,w:40,h:50}];
    enemySpeeds=[2,-2];
    coins=[{x:350,y:200,w:30,h:30},{x:600,y:150,w:30,h:30}];
    door.x=730; door.y=270;
  } else if(lvl===4){
    bossHealth=10; bossSpeed=2;
    boss.x=250; boss.y=250;
    door.x=-200; door.y=-200;
  } else if(lvl===5){
    door.x=730; door.y=270;
  }

  const mCount = lvl<=3 ? 5 : 8;
  for(let i=0;i<mCount;i++){
    meteors.push({x:rnd(50,W-50),y:rnd(-600,-50),w:40,h:40});
  }
}

function resetGame(){
  level=1; score=0; velY=0; onGround=false;
  loadLevel(level);
  showEnd(false);
  state='playing';
  updateHUD();
}

function goMenu(){
  stopMusic();
  state='menu';
  document.getElementById('menu-overlay').classList.remove('hidden');
  showEnd(false);
  updateHUD();
}

function showEnd(show){
  document.getElementById('end-overlay').style.display=show?'flex':'none';
}

function updateHUD(){
  document.getElementById('hud-score').textContent=score;
  document.getElementById('hud-level').textContent=state==='playing'?level:'—';
  document.getElementById('hud-boss').textContent=(state==='playing'&&level===4)?bossHealth+'♥':'—';
}

// ── Menu events ────────────────────────────────────────────
document.querySelectorAll('.lvl-btn').forEach(btn=>{
  btn.addEventListener('click',()=>{
    level=parseInt(btn.dataset.lvl);
    score=0;
    loadLevel(level);
    document.getElementById('menu-overlay').classList.add('hidden');
    state='playing';
    updateHUD();
  });
});
document.getElementById('music-toggle-menu').addEventListener('click',toggleMusic);
document.getElementById('btn-music').addEventListener('click',toggleMusic);
document.getElementById('btn-show-menu').addEventListener('click',goMenu);
document.getElementById('btn-restart').addEventListener('click',resetGame);
document.getElementById('btn-menu').addEventListener('click',goMenu);

// ── Draw helpers ───────────────────────────────────────────
function drawBackground(){
  drawImg('nebo.png',0,0,W,H,'#0a0520');
}

function drawRect(x,y,w,h,col){
  ctx.fillStyle=col; ctx.fillRect(x,y,w,h);
}

// ── Game over / win display ────────────────────────────────
function triggerEnd(type){
  state=type;
  stopMusic();
  const overlay=document.getElementById('end-overlay');
  const titleEl=document.getElementById('end-title');
  const scoreEl=document.getElementById('end-score');
  if(type==='over'){
    overlay.style.background='rgba(80,0,0,0.75)';
    titleEl.style.background='linear-gradient(135deg,#ff2d78,#ff6b35)';
    titleEl.style.webkitBackgroundClip='text';
    titleEl.style.webkitTextFillColor='transparent';
    titleEl.textContent='GAME OVER';
  } else {
    overlay.style.background='rgba(0,60,30,0.75)';
    titleEl.style.background='linear-gradient(135deg,#00f260,#00d4ff)';
    titleEl.style.webkitBackgroundClip='text';
    titleEl.style.webkitTextFillColor='transparent';
    titleEl.textContent='YOU WIN!';
  }
  scoreEl.textContent='Score: '+score;
  showEnd(true);
  updateHUD();
}

// ── Main loop ──────────────────────────────────────────────
let last=0;
function loop(ts){
  const dt=Math.min((ts-last)/16.67,3); last=ts;
  requestAnimationFrame(loop);

  if(state==='menu'){
    // draw menu background
    drawImg('fondino.png',0,0,W,H,'#050210');
    // glowing title
    ctx.save();
    ctx.font='bold 52px Orbitron,sans-serif';
    ctx.textAlign='center';
    ctx.shadowColor='rgba(0,212,255,0.8)';
    ctx.shadowBlur=20;
    const grad=ctx.createLinearGradient(200,0,600,0);
    grad.addColorStop(0,'#7c3aff'); grad.addColorStop(1,'#00d4ff');
    ctx.fillStyle=grad;
    ctx.fillText('DINO ADVENTURE',W/2,120);
    ctx.restore();
    return;
  }

  if(state==='playing'){
    // Music
    if(musicOn) playMusic(level===4?'boss':'game');

    // ── Input ────────────────────────────────────
    if(keys['KeyA'] && player.x>0){ player.x-=SPEED; facingLeft=true; }
    if(keys['KeyD'] && player.x<W-player.w){ player.x+=SPEED; facingLeft=false; }
    if(keys['Space'] && onGround){ velY=JUMP; onGround=false; }

    // ── Physics ───────────────────────────────────
    velY+=GRAV;
    player.y+=velY;
    onGround=false;

    // ground
    const gr=ground;
    if(player.x+player.w>gr.x && player.x<gr.x+gr.w &&
       player.y+player.h>gr.y && player.y<gr.y+gr.h && velY>=0){
      player.y=gr.y-player.h; velY=0; onGround=true;
    }
    // platforms
    if(!onGround){
      for(const p of platforms){
        if(player.x+player.w>p.x && player.x<p.x+p.w &&
           player.y+player.h>p.y && player.y+player.h<p.y+p.h+15 && velY>=0){
          player.y=p.y-player.h; velY=0; onGround=true; break;
        }
      }
    }

    // ── Enemies ───────────────────────────────────
    for(let i=0;i<enemies.length;i++){
      enemies[i].x+=enemySpeeds[i];
      if(enemies[i].x<0||enemies[i].x+enemies[i].w>W) enemySpeeds[i]*=-1;
      if(rectsCollide(player,enemies[i])){ triggerEnd('over'); return; }
    }

    // ── Coins ────────────────────────────────────
    for(let i=coins.length-1;i>=0;i--){
      if(rectsCollide(player,coins[i])){
        coins.splice(i,1); score+=10;
        audioCoin.currentTime=0; audioCoin.play().catch(()=>{});
        updateHUD();
      }
    }

    // ── Meteors ──────────────────────────────────
    for(const m of meteors){
      m.y+=2;
      if(m.y>H){ m.y=rnd(-600,-50); m.x=rnd(50,W-50); }
      if(rectsCollide(player,m)){ triggerEnd('over'); return; }
    }

    // ── Boss ─────────────────────────────────────
    if(level===4){
      boss.x+=bossSpeed;
      if(boss.x<0||boss.x+boss.w>W) bossSpeed*=-1;
      if(rectsCollide(player,boss)){
        if(velY>0 && player.y+player.h<boss.y+40){
          bossHealth--; velY=-12;
          player.y=boss.y-player.h;
          updateHUD();
        } else { triggerEnd('over'); return; }
      }
      if(bossHealth<=0){ score+=100; triggerEnd('win'); return; }
    }

    // ── Door / level advance ──────────────────────
    if(level!==4 && rectsCollide(player,door)){
      level++;
      if(level>5){ triggerEnd('win'); return; }
      loadLevel(level); updateHUD();
    }

    // fall off screen
    if(player.y>H){ triggerEnd('over'); return; }
  }

  // ── RENDER ────────────────────────────────────────────────
  drawBackground();

  // ground
  ctx.fillStyle='#22882a'; ctx.fillRect(ground.x,ground.y,ground.w,ground.h);
  // platforms
  for(const p of platforms){
    ctx.fillStyle='#8B4513'; ctx.fillRect(p.x,p.y,p.w,p.h);
    ctx.fillStyle='#5a2d0c'; ctx.fillRect(p.x,p.y,p.w,4);
  }

  // door
  if(level!==4) drawImg('door.png',door.x,door.y,door.w,door.h,'#6b3200');

  // enemies
  for(const e of enemies) drawImg('kasktus1.png',e.x,e.y,e.w,e.h,'#ff3300');

  // coins
  for(const c of coins){
    ctx.save();
    ctx.shadowColor='rgba(255,220,0,0.8)'; ctx.shadowBlur=10;
    drawImg('coin.png',c.x,c.y,c.w,c.h,'#ffd700');
    ctx.restore();
  }

  // meteors
  for(const m of meteors){
    ctx.save();
    ctx.shadowColor='rgba(255,100,0,0.6)'; ctx.shadowBlur=8;
    drawImg('meteor.png',m.x,m.y,m.w,m.h,'#664400');
    ctx.restore();
  }

  // boss
  if(level===4){
    ctx.save();
    ctx.shadowColor='rgba(255,0,100,0.7)'; ctx.shadowBlur=18;
    drawImg('boss.png',boss.x,boss.y,boss.w,boss.h,'#cc0044');
    ctx.restore();
    // health bar
    const bw=boss.w*(Math.max(0,bossHealth)/10);
    ctx.fillStyle='rgba(0,0,0,0.5)'; ctx.fillRect(boss.x,boss.y-22,boss.w,12);
    ctx.fillStyle='#ff2d78'; ctx.fillRect(boss.x,boss.y-22,bw,12);
    ctx.strokeStyle='rgba(255,45,120,0.5)'; ctx.lineWidth=1;
    ctx.strokeRect(boss.x,boss.y-22,boss.w,12);
  }

  // player
  const pimg = facingLeft?'dino1.png':'dino.png';
  ctx.save();
  ctx.shadowColor='rgba(0,212,255,0.5)'; ctx.shadowBlur=12;
  drawImg(pimg,player.x,player.y,player.w,player.h,'#00cc44');
  ctx.restore();

  // HUD in-canvas
  ctx.font='bold 18px Orbitron,sans-serif';
  ctx.fillStyle='rgba(0,0,0,0.5)';
  ctx.fillRect(6,6,180,52);
  ctx.strokeStyle='rgba(0,212,255,0.25)'; ctx.lineWidth=1;
  ctx.strokeRect(6,6,180,52);
  ctx.fillStyle='#00d4ff';
  ctx.shadowColor='rgba(0,212,255,0.8)'; ctx.shadowBlur=8;
  ctx.fillText('⭐ '+score, 16, 30);
  ctx.fillStyle='rgba(200,180,255,0.8)'; ctx.shadowBlur=0;
  ctx.font='13px Rajdhani,sans-serif';
  const lvlLabels=['','LEVEL 1','LEVEL 2','LEVEL 3','BOSS','🚂 TRAIN'];
  ctx.fillText(lvlLabels[level]||('LEVEL '+level), 16, 50);
}

requestAnimationFrame(loop);
updateHUD();
</script>
</body>
</html>
