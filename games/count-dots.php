<div class="text-center mb-4">
    <div class="score-bar justify-content-center">
        <div class="score-box"><span class="score-num" id="cd-score">0</span><span class="score-label">Score</span></div>
        <div class="score-box"><span class="score-num" id="cd-level">1</span><span class="score-label">Level</span></div>
        <div class="score-box"><span class="score-num" id="cd-lives">❤️❤️❤️</span><span class="score-label">Lives</span></div>
    </div>
    <div id="cd-status" style="font-family:var(--font-display);font-size:14px;color:var(--text-muted);letter-spacing:1px;margin-bottom:20px">Count the dots that flash on screen!</div>
    <button class="btn-game btn-game-primary" id="cd-start">▶ Start</button>
</div>
<div id="cd-arena" style="position:relative;width:100%;max-width:500px;height:300px;margin:0 auto;background:var(--bg-card2);border-radius:16px;border:1px solid var(--border);display:none;overflow:hidden"></div>
<div id="cd-answer-area" style="display:none;text-align:center;margin-top:20px">
    <p style="color:var(--text-muted);margin-bottom:12px">How many dots did you see?</p>
    <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap" id="cd-options"></div>
</div>
<script>
(function(){
    const DIFF={Easy:{baseMin:3,baseMax:8,showTime:2500,lives:3},Medium:{baseMin:5,baseMax:15,showTime:1800,lives:3},Hard:{baseMin:8,baseMax:25,showTime:1200,lives:2}};
    const diff=typeof DIFFICULTY!=='undefined'?DIFFICULTY:'Medium';
    const {baseMin,baseMax,showTime,lives:maxLives}=DIFF[diff]||DIFF.Medium;
    let score=0,level=1,lives=maxLives,dotCount=0,active=false;
    function updateLives(){document.getElementById('cd-lives').textContent='❤️'.repeat(Math.max(0,lives));}
    function setStatus(t,c='var(--text-muted)'){const el=document.getElementById('cd-status');el.textContent=t;el.style.color=c;}
    function start(){document.getElementById('cd-start').style.display='none';score=0;level=1;lives=maxLives;document.getElementById('cd-score').textContent=0;document.getElementById('cd-level').textContent=1;updateLives();newRound();}
    async function newRound(){
        active=false;document.getElementById('cd-answer-area').style.display='none';
        const arena=document.getElementById('cd-arena');arena.innerHTML='';arena.style.display='block';
        dotCount=baseMin+Math.floor(Math.random()*(baseMax-baseMin+level));
        const displayTime=Math.max(600,showTime-level*60);setStatus('👀 Count fast!','var(--accent-cyan)');
        const colors=['#6c63ff','#00b4d8','#f7931e','#38b000','#ff006e'];
        for(let i=0;i<dotCount;i++){
            const dot=document.createElement('div');const sz=Math.random()*14+10;
            const x=Math.random()*(arena.offsetWidth-sz),y=Math.random()*(arena.offsetHeight-sz);
            const col=colors[Math.floor(Math.random()*colors.length)];
            dot.style.cssText=`position:absolute;width:${sz}px;height:${sz}px;border-radius:50%;left:${x}px;top:${y}px;background:radial-gradient(circle,${col},${col}99);box-shadow:0 0 8px ${col}80`;
            arena.appendChild(dot);
        }
        await new Promise(r=>setTimeout(r,displayTime));arena.innerHTML='';arena.style.display='none';showOptions();
    }
    function showOptions(){
        setStatus('How many dots?','var(--accent-orange)');
        const opts=new Set([dotCount]);
        while(opts.size<4){const v=dotCount+Math.floor(Math.random()*9)-4;if(v>0&&v!==dotCount)opts.add(v);}
        const container=document.getElementById('cd-options');container.innerHTML='';
        [...opts].sort(()=>Math.random()-.5).forEach(opt=>{
            const btn=document.createElement('button');
            btn.style.cssText='padding:16px 28px;border-radius:12px;font-family:var(--font-display);font-size:22px;font-weight:700;border:2px solid var(--border);background:var(--bg-card2);color:var(--text-main);cursor:pointer;transition:all 0.15s;min-width:80px';
            btn.textContent=opt;
            btn.onmouseenter=()=>{btn.style.borderColor='var(--primary)';btn.style.color='var(--primary)';};
            btn.onmouseleave=()=>{btn.style.borderColor='var(--border)';btn.style.color='var(--text-main)';};
            btn.onclick=()=>checkAnswer(opt,btn);container.appendChild(btn);
        });
        document.getElementById('cd-answer-area').style.display='block';active=true;
    }
    function checkAnswer(val,btn){
        if(!active)return;active=false;
        document.querySelectorAll('#cd-options button').forEach(b=>{if(parseInt(b.textContent)===dotCount){b.style.background='rgba(56,176,0,0.2)';b.style.borderColor='var(--accent-green)';b.style.color='var(--accent-green)';}});
        if(val===dotCount){
            score+=level*5;document.getElementById('cd-score').textContent=score;level++;document.getElementById('cd-level').textContent=level;
            setStatus('✅ Correct!','var(--accent-green)');setTimeout(newRound,900);
        } else {
            btn.style.background='rgba(255,0,110,0.15)';btn.style.borderColor='var(--accent-pink)';btn.style.color='var(--accent-pink)';
            lives--;updateLives();
            if(lives<=0){setStatus(`❌ Game Over! Score: ${score}`,'var(--accent-pink)');if(typeof submitScore==='function')submitScore(score);setTimeout(()=>{document.getElementById('cd-answer-area').style.display='none';document.getElementById('cd-start').textContent='Play Again';document.getElementById('cd-start').style.display='inline-block';score=0;level=1;lives=maxLives;document.getElementById('cd-score').textContent=0;document.getElementById('cd-level').textContent=1;updateLives();setStatus('Count the dots that flash on screen!');},1500);}
            else{setStatus(`❌ It was ${dotCount}! ${lives} lives left`,'var(--accent-pink)');setTimeout(newRound,1200);}
        }
    }
    document.getElementById('cd-start').addEventListener('click',start);
})();
</script>
