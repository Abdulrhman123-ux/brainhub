<div class="text-center mb-4">
    <div class="score-bar justify-content-center">
        <div class="score-box"><span class="score-num" id="rt2-score">0</span><span class="score-label">Score</span></div>
        <div class="score-box"><span class="score-num" id="rt2-level">1</span><span class="score-label">Level</span></div>
        <div class="score-box"><span class="score-num" id="rt2-accuracy">-</span><span class="score-label">Accuracy</span></div>
        <div class="score-box"><span class="score-num" id="rt2-lives">❤️❤️❤️</span><span class="score-label">Lives</span></div>
    </div>
    <div id="rt2-status" style="font-family:var(--font-display);font-size:14px;color:var(--text-muted);letter-spacing:1px;margin-bottom:20px">Listen to the rhythm, then tap it back!</div>
    <button class="btn-game btn-game-primary" id="rt2-start">▶ Start</button>
</div>
<div id="rt2-game" style="display:none;text-align:center">
    <div id="rt2-visualizer" style="display:flex;gap:8px;justify-content:center;margin-bottom:28px;min-height:48px;align-items:center"></div>
    <div id="rt2-tap-btn-wrap" style="display:none">
        <button id="rt2-tap" class="btn-game btn-game-primary" style="width:160px;height:160px;border-radius:50%;font-size:24px;box-shadow:0 0 40px var(--primary-glow)">TAP</button>
        <p style="color:var(--text-muted);font-size:13px;margin-top:12px">Or press <kbd style="background:var(--bg-card2);border:1px solid var(--border);border-radius:6px;padding:2px 8px;color:var(--text-main)">Space</kbd></p>
        <button class="btn-game btn-game-secondary mt-3" id="rt2-done">Done ✓</button>
    </div>
</div>
<script>
(function(){
    const DIFF={Easy:{beats:3,intervals:[300,400,500],minAcc:50},Medium:{beats:5,intervals:[200,300,400,500],minAcc:60},Hard:{beats:7,intervals:[150,200,300,400],minAcc:70}};
    const diff=typeof DIFFICULTY!=='undefined'?DIFFICULTY:'Medium';
    const {beats,intervals,minAcc}=DIFF[diff]||DIFF.Medium;
    let level=1,score=0,lives=3,pattern=[],tapTimes=[],startTime=0,phase='idle',ctx=null;
    function getCtx(){if(!ctx)try{ctx=new(window.AudioContext||window.webkitAudioContext)();}catch(e){}return ctx;}
    function playClick(freq=440,dur=80){try{const c=getCtx();if(!c)return;const o=c.createOscillator(),g=c.createGain();o.connect(g);g.connect(c.destination);o.frequency.value=freq;o.type='square';g.gain.setValueAtTime(0.15,c.currentTime);g.gain.exponentialRampToValueAtTime(0.001,c.currentTime+dur/1000);o.start();o.stop(c.currentTime+dur/1000);}catch(e){}}
    function setStatus(t,c='var(--text-muted)'){const el=document.getElementById('rt2-status');el.textContent=t;el.style.color=c;}
    function updateLives(){document.getElementById('rt2-lives').textContent='❤️'.repeat(Math.max(0,lives));}
    function gen(){pattern=[];for(let i=0;i<beats+level-1;i++)pattern.push(intervals[Math.floor(Math.random()*intervals.length)]);}
    function start(){document.getElementById('rt2-start').style.display='none';document.getElementById('rt2-game').style.display='block';level=1;score=0;lives=3;document.getElementById('rt2-level').textContent=1;document.getElementById('rt2-score').textContent=0;updateLives();newRound();}
    function newRound(){gen();tapTimes=[];phase='playing';document.getElementById('rt2-tap-btn-wrap').style.display='none';setStatus('👂 Listen carefully…','var(--accent-cyan)');playPattern();}
    function playPattern(){
        const viz=document.getElementById('rt2-visualizer');viz.innerHTML='';
        pattern.forEach(()=>{const d=document.createElement('div');d.style.cssText='width:14px;height:14px;border-radius:50%;background:var(--border);transition:all 0.1s';viz.appendChild(d);});
        const dots=[...viz.children];let elapsed=0;
        pattern.forEach((interval,i)=>{elapsed+=interval;setTimeout(()=>{dots[i].style.background='var(--primary)';dots[i].style.boxShadow='0 0 12px var(--primary-glow)';dots[i].style.transform='scale(1.5)';playClick(440,100);setTimeout(()=>{dots[i].style.transform='scale(1)';},150);if(i===pattern.length-1)setTimeout(startTapping,600);},elapsed);});
    }
    function startTapping(){phase='tapping';tapTimes=[];startTime=0;document.getElementById('rt2-tap-btn-wrap').style.display='block';setStatus('🥁 Tap the same rhythm!','var(--accent-orange)');}
    function recordTap(){
        if(phase!=='tapping')return;
        const now=performance.now();
        if(tapTimes.length===0)startTime=now;
        tapTimes.push(now);
        playClick(660,80);
        const btn=document.getElementById('rt2-tap');btn.style.transform='scale(0.9)';setTimeout(()=>btn.style.transform='scale(1)',100);
        if(tapTimes.length>=pattern.length)setTimeout(evaluate,300);
    }
    function evaluate(){
        phase='result';document.getElementById('rt2-tap-btn-wrap').style.display='none';
        const myIntervals=[];for(let i=1;i<tapTimes.length;i++)myIntervals.push(tapTimes[i]-tapTimes[i-1]);
        const targetLen=Math.min(myIntervals.length,pattern.length-1);
        let totalError=0;
        for(let i=0;i<targetLen;i++)totalError+=Math.abs(myIntervals[i]-pattern[i+1])/pattern[i+1];
        const accuracy=targetLen>0?Math.max(0,Math.round((1-totalError/targetLen)*100)):0;
        document.getElementById('rt2-accuracy').textContent=accuracy+'%';
        if(accuracy>=minAcc){
            const pts=level*10+accuracy;score+=pts;document.getElementById('rt2-score').textContent=score;level++;document.getElementById('rt2-level').textContent=level;
            setStatus(`✅ ${accuracy}% accuracy! +${pts} pts`,'var(--accent-green)');setTimeout(newRound,1200);
        } else {
            lives--;updateLives();setStatus(`${accuracy}% — need ${minAcc}%! ${lives} lives left`,'var(--accent-pink)');
            if(lives<=0){if(typeof submitScore==='function')submitScore(score);setTimeout(()=>{document.getElementById('rt2-game').style.display='none';document.getElementById('rt2-start').textContent='Play Again';document.getElementById('rt2-start').style.display='inline-block';level=1;score=0;lives=3;updateLives();setStatus('Listen to the rhythm, then tap it back!');},2000);}
            else setTimeout(newRound,1500);
        }
    }
    document.getElementById('rt2-start').addEventListener('click',start);
    document.getElementById('rt2-tap').addEventListener('click',recordTap);
    document.getElementById('rt2-done').addEventListener('click',evaluate);
    document.addEventListener('keydown',e=>{if(e.code==='Space'&&phase==='tapping'){e.preventDefault();recordTap();}});
})();
</script>
