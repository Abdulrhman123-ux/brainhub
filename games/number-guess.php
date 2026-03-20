<div class="text-center mb-4">
    <div class="score-bar justify-content-center">
        <div class="score-box"><span class="score-num" id="ng-guesses">0</span><span class="score-label">Guesses</span></div>
        <div class="score-box"><span class="score-num" id="ng-range">-</span><span class="score-label">Range</span></div>
        <div class="score-box"><span class="score-num" id="ng-best">---</span><span class="score-label">Best</span></div>
    </div>
    <div id="ng-status" style="font-family:var(--font-display);font-size:15px;color:var(--text-muted);margin-bottom:20px">I'm thinking of a number…</div>
    <button class="btn-game btn-game-primary" id="ng-start" style="display:none">New Game</button>
</div>
<div id="ng-game" style="display:none;text-align:center">
    <div id="ng-hint" class="hint-text" style="display:none"></div>
    <div id="ng-history" style="display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin-bottom:20px;min-height:40px"></div>
    <div style="display:flex;gap:12px;justify-content:center;align-items:center">
        <input type="number" class="guess-input" id="ng-input" placeholder="?">
        <button class="btn-game btn-game-primary" id="ng-submit">Guess!</button>
    </div>
    <div id="ng-therm-wrap" style="margin-top:24px;max-width:320px;margin-left:auto;margin-right:auto">
        <div style="height:12px;border-radius:6px;background:linear-gradient(90deg,#00b4d8,#38b000,#f7931e,#ff3355);position:relative">
            <div id="ng-marker" style="position:absolute;top:-6px;width:24px;height:24px;border-radius:50%;background:white;border:3px solid var(--primary);transform:translateX(-50%);left:50%;transition:left 0.4s ease;box-shadow:0 0 10px var(--primary-glow)"></div>
        </div>
        <div style="display:flex;justify-content:space-between;margin-top:6px;font-size:12px;color:var(--text-muted)"><span>← Cold</span><span>Hot →</span></div>
    </div>
</div>
<script>
(function(){
    const DIFF={Easy:{max:50,label:'1–50'},Medium:{max:100,label:'1–100'},Hard:{max:200,label:'1–200'}};
    const diff=typeof DIFFICULTY!=='undefined'?DIFFICULTY:'Medium';
    const {max,label}=DIFF[diff]||DIFF.Medium;
    let secret=0,guesses=0,best=Infinity,active=false;
    document.getElementById('ng-range').textContent=label;
    function setStatus(t,c='var(--text-muted)'){const el=document.getElementById('ng-status');el.textContent=t;el.style.color=c;}
    function showHint(text,cls){const h=document.getElementById('ng-hint');h.textContent=text;h.className='hint-text '+cls;h.style.display='block';}
    function startGame(){
        secret=Math.floor(Math.random()*max)+1;guesses=0;active=true;
        document.getElementById('ng-guesses').textContent=0;document.getElementById('ng-history').innerHTML='';document.getElementById('ng-hint').style.display='none';
        document.getElementById('ng-game').style.display='block';document.getElementById('ng-start').style.display='none';
        document.getElementById('ng-input').value='';document.getElementById('ng-input').disabled=false;document.getElementById('ng-submit').disabled=false;
        setStatus(`I'm thinking of a number between 1 and ${max}…`);document.getElementById('ng-marker').style.left='50%';
    }
    function submitGuess(){
        if(!active)return;
        const val=parseInt(document.getElementById('ng-input').value);
        if(isNaN(val)||val<1||val>max){setStatus(`Enter a number between 1 and ${max}!`,'var(--accent-pink)');return;}
        guesses++;document.getElementById('ng-guesses').textContent=guesses;
        document.getElementById('ng-marker').style.left=Math.round((val/max)*100)+'%';
        const hist=document.getElementById('ng-history');
        const tag=document.createElement('span');const diff2=Math.abs(val-secret);
        const col=diff2===0?'var(--accent-green)':diff2<=5?'#ff6400':diff2<=15?'var(--accent-orange)':'var(--accent-cyan)';
        tag.style.cssText=`padding:5px 14px;border-radius:50px;background:rgba(255,255,255,0.04);border:1px solid ${col}50;color:${col};font-family:var(--font-display);font-size:13px;font-weight:700`;
        tag.textContent=val;hist.appendChild(tag);
        if(val===secret){
            active=false;showHint(`🎉 Correct! It was ${secret}!`,'hint-win');
            const score=Math.max(10,Math.round(1000-(guesses*50)));
            setStatus(`Got it in ${guesses} guess${guesses!==1?'es':''}! Score: ${score}`,'var(--accent-green)');
            if(guesses<best){best=guesses;document.getElementById('ng-best').textContent=guesses;}
            document.getElementById('ng-input').disabled=true;document.getElementById('ng-submit').disabled=true;
            document.getElementById('ng-start').style.display='inline-block';
            if(typeof submitScore==='function')submitScore(score);
        } else {
            const dir=val<secret?'⬆️ Higher!':'⬇️ Lower!';const d2=Math.abs(val-secret);
            if(d2<=Math.ceil(max*0.05))showHint(`🔥 ${dir} (Burning hot!)`,'hint-hot');
            else if(d2<=Math.ceil(max*0.15))showHint(`♨️ ${dir} (Warm)`,'hint-warm');
            else showHint(`🧊 ${dir} (Cold)`,'hint-cold');
            setStatus(dir,'var(--text-muted)');
        }
        document.getElementById('ng-input').value='';document.getElementById('ng-input').focus();
    }
    document.getElementById('ng-submit').addEventListener('click',submitGuess);
    document.getElementById('ng-start').addEventListener('click',startGame);
    document.getElementById('ng-input').addEventListener('keydown',e=>{if(e.key==='Enter')submitGuess();});
    document.getElementById('ng-game').style.display='block';startGame();
})();
</script>
