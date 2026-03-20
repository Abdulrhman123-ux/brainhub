<div class="text-center mb-4">
    <div class="score-bar justify-content-center">
        <div class="score-box"><span class="score-num" id="at-score">0</span><span class="score-label">Hits</span></div>
        <div class="score-box"><span class="score-num" id="at-misses">0</span><span class="score-label">Misses</span></div>
        <div class="score-box"><span class="score-num" id="at-timer">30</span><span class="score-label">Time</span></div>
        <div class="score-box"><span class="score-num" id="at-acc">-</span><span class="score-label">Accuracy</span></div>
    </div>
    <button class="btn-game btn-game-primary" id="at-start">▶ Start</button>
</div>
<div id="at-arena" style="display:none;position:relative;width:100%;max-width:620px;height:380px;margin:0 auto;background:var(--bg-card2);border-radius:16px;border:1px solid var(--border);overflow:hidden;cursor:crosshair" onclick="handleArenaMiss(event)"></div>
<div id="at-over" class="text-center" style="display:none">
    <div style="font-size:60px">🎯</div><h3 class="section-title">Time's Up!</h3>
    <p style="color:var(--text-muted)" class="mb-3">Hits: <strong id="at-final-hits" style="color:var(--accent-green)"></strong> | Accuracy: <strong id="at-final-acc" style="color:var(--accent-cyan)"></strong></p>
    <button class="btn-game btn-game-primary" id="at-restart">Play Again</button>
</div>
<script>
(function(){
    const DIFF={Easy:{time:40,targetSize:[45,65],shrink:false},Medium:{time:30,targetSize:[30,50],shrink:false},Hard:{time:25,targetSize:[20,38],shrink:true}};
    const diff=typeof DIFFICULTY!=='undefined'?DIFFICULTY:'Medium';
    const {time:totalTime,targetSize:[minSz,maxSz],shrink}=DIFF[diff]||DIFF.Medium;
    let hits=0,misses=0,timer,timeLeft=totalTime,target=null,active=false;
    window.handleArenaMiss=function(e){if(!active)return;if(e.target===document.getElementById('at-arena')){misses++;update();}};
    function update(){document.getElementById('at-score').textContent=hits;document.getElementById('at-misses').textContent=misses;const t=hits+misses;document.getElementById('at-acc').textContent=t>0?Math.round(hits/t*100)+'%':'-';}
    function spawnTarget(){
        const arena=document.getElementById('at-arena');
        if(target){target.remove();target=null;}
        const size=Math.floor(Math.random()*(maxSz-minSz))+minSz;
        const x=Math.random()*(arena.offsetWidth-size);const y=Math.random()*(arena.offsetHeight-size);
        const t=document.createElement('div');
        t.style.cssText=`position:absolute;left:${x}px;top:${y}px;width:${size}px;height:${size}px;border-radius:50%;background:radial-gradient(circle,#ff3355,#cc0033);border:3px solid rgba(255,255,255,0.3);cursor:crosshair;display:flex;align-items:center;justify-content:center;font-size:${Math.max(12,size*0.4)}px;transition:transform 0.1s;box-shadow:0 0 20px rgba(255,0,80,0.5)`;
        t.textContent='🎯';
        t.onclick=e=>{e.stopPropagation();hits++;update();spawnTarget();};
        t.onmouseenter=()=>t.style.transform='scale(1.1)';t.onmouseleave=()=>t.style.transform='scale(1)';
        if(shrink){let s=size;const shrinkInt=setInterval(()=>{s-=0.5;t.style.width=s+'px';t.style.height=s+'px';if(s<=8){clearInterval(shrinkInt);misses++;update();spawnTarget();}},30);}
        arena.appendChild(t);target=t;
    }
    function start(){
        document.getElementById('at-start').style.display='none';document.getElementById('at-over').style.display='none';
        const arena=document.getElementById('at-arena');arena.style.display='block';
        hits=0;misses=0;timeLeft=totalTime;active=true;update();
        document.getElementById('at-timer').textContent=totalTime;
        clearInterval(timer);timer=setInterval(()=>{timeLeft--;document.getElementById('at-timer').textContent=timeLeft;if(timeLeft<=0){clearInterval(timer);endGame();}},1000);
        spawnTarget();
    }
    function endGame(){
        active=false;if(target){target.remove();target=null;}
        document.getElementById('at-arena').style.display='none';
        const total=hits+misses;const acc=total>0?Math.round(hits/total*100):0;
        const score=hits*10+Math.round(acc*0.5);
        document.getElementById('at-final-hits').textContent=hits;document.getElementById('at-final-acc').textContent=acc+'%';
        document.getElementById('at-over').style.display='block';
        if(typeof submitScore==='function')submitScore(score);
    }
    document.getElementById('at-start').addEventListener('click',start);
    document.getElementById('at-restart').addEventListener('click',start);
})();
</script>
