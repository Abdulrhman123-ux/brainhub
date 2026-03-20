<div class="text-center mb-4">
    <div class="score-bar justify-content-center">
        <div class="score-box"><span class="score-num" id="ss-level">0</span><span class="score-label">Level</span></div>
        <div class="score-box"><span class="score-num" id="ss-best">0</span><span class="score-label">Best</span></div>
    </div>
    <div id="ss-status" style="font-family:var(--font-display);font-size:15px;color:var(--text-muted);letter-spacing:1px;margin-bottom:20px">Watch the sequence carefully!</div>
    <button class="btn-game btn-game-primary" id="ss-start">▶ Start Simon</button>
</div>
<div class="simon-board" id="simon-board" style="pointer-events:none">
    <button class="simon-btn" id="sb-green"  onclick="simonClick('green')"></button>
    <button class="simon-btn" id="sb-red"    onclick="simonClick('red')"></button>
    <button class="simon-btn" id="sb-yellow" onclick="simonClick('yellow')"></button>
    <button class="simon-btn" id="sb-blue"   onclick="simonClick('blue')"></button>
</div>
<div id="ss-over" class="text-center mt-4" style="display:none">
    <div style="font-size:48px">💔</div>
    <h3 class="section-title">Wrong!</h3>
    <p style="color:var(--text-muted)" class="mb-2">You reached level <strong id="ss-final" style="color:var(--primary)"></strong></p>
    <p style="color:var(--accent-green);font-family:var(--font-display);font-size:18px;font-weight:700">Score: <span id="ss-score-display"></span></p>
    <button class="btn-game btn-game-primary mt-3" id="ss-restart">Try Again</button>
</div>
<script>
(function(){
    const SPEED_MAP={Easy:700,Medium:550,Hard:380};
    const diff=typeof DIFFICULTY!=='undefined'?DIFFICULTY:'Medium';
    const baseSpeed=SPEED_MAP[diff]||550;
    const colors=['green','red','yellow','blue'];
    const sounds={green:261.63,red:329.63,yellow:392,blue:523.25};
    let sequence=[],playerIdx=0,level=0,best=0,playing=false,ctx=null;
    function getCtx(){if(!ctx)try{ctx=new(window.AudioContext||window.webkitAudioContext)();}catch(e){}return ctx;}
    function playTone(freq,dur=200){try{const c=getCtx();if(!c)return;const o=c.createOscillator(),g=c.createGain();o.connect(g);g.connect(c.destination);o.frequency.value=freq;o.type='sine';g.gain.setValueAtTime(0.3,c.currentTime);g.gain.exponentialRampToValueAtTime(0.001,c.currentTime+dur/1000);o.start();o.stop(c.currentTime+dur/1000);}catch(e){}}
    function flashBtn(color,dur){return new Promise(res=>{const btn=document.getElementById('sb-'+color);btn.classList.add('active');playTone(sounds[color],dur-50);setTimeout(()=>{btn.classList.remove('active');setTimeout(res,80);},dur);});}
    function setStatus(t,c='var(--text-muted)'){const el=document.getElementById('ss-status');el.textContent=t;el.style.color=c;}
    async function playSequence(){
        playing=true;document.getElementById('simon-board').style.pointerEvents='none';
        setStatus('👀 Watch!','var(--accent-cyan)');
        await new Promise(r=>setTimeout(r,500));
        const speed=Math.max(220,baseSpeed-level*15);
        for(const color of sequence){await flashBtn(color,speed);await new Promise(r=>setTimeout(r,80));}
        playing=false;document.getElementById('simon-board').style.pointerEvents='all';
        playerIdx=0;setStatus('🎯 Repeat!','var(--accent-orange)');
    }
    function start(){
        document.getElementById('ss-start').style.display='none';
        document.getElementById('ss-over').style.display='none';
        sequence=[];level=0;document.getElementById('ss-level').textContent=0;
        addAndPlay();
    }
    function addAndPlay(){
        sequence.push(colors[Math.floor(Math.random()*4)]);
        level++;document.getElementById('ss-level').textContent=level;
        playSequence();
    }
    window.simonClick=function(color){
        if(playing)return;
        flashBtn(color,200);
        if(color===sequence[playerIdx]){
            playerIdx++;
            if(playerIdx===sequence.length){
                setStatus('✅ Correct!','var(--accent-green)');
                if(level>best){best=level;document.getElementById('ss-best').textContent=best;}
                setTimeout(addAndPlay,900);
            }
        } else {
            playing=true;playTone(110,600);
            setStatus(`❌ Wrong at level ${level}!`,'var(--accent-pink)');
            document.getElementById('simon-board').style.pointerEvents='none';
            const score=level*10*(diff==='Hard'?3:diff==='Medium'?2:1);
            setTimeout(()=>{
                document.getElementById('ss-final').textContent=level;
                document.getElementById('ss-score-display').textContent=score;
                document.getElementById('ss-over').style.display='block';
                if(typeof submitScore==='function')submitScore(score);
            },700);
        }
    };
    document.getElementById('ss-start').addEventListener('click',start);
    document.getElementById('ss-restart').addEventListener('click',start);
})();
</script>
