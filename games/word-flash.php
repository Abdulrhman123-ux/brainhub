<div class="text-center mb-4">
    <div class="score-bar justify-content-center">
        <div class="score-box"><span class="score-num" id="wf-score">0</span><span class="score-label">Score</span></div>
        <div class="score-box"><span class="score-num" id="wf-round">1</span><span class="score-label">Round</span></div>
        <div class="score-box"><span class="score-num" id="wf-lives">❤️❤️❤️</span><span class="score-label">Lives</span></div>
    </div>
    <div id="wf-status" style="font-family:var(--font-display);font-size:14px;color:var(--text-muted);letter-spacing:1px;margin-bottom:20px">Words will flash — memorize them all!</div>
    <button class="btn-game btn-game-primary" id="wf-start">▶ Start Game</button>
</div>

<div id="wf-flash-area" style="display:none;text-align:center;min-height:160px;display:none;align-items:center;justify-content:center;flex-direction:column">
    <div id="wf-word" style="font-family:var(--font-display);font-size:clamp(2rem,6vw,4rem);font-weight:900;color:var(--primary);text-shadow:0 0 30px var(--primary-glow);letter-spacing:4px;transition:opacity 0.2s"></div>
    <div id="wf-counter" style="font-size:13px;color:var(--text-muted);margin-top:12px;letter-spacing:2px"></div>
</div>

<div id="wf-input-area" style="display:none;text-align:center">
    <p style="color:var(--text-muted);margin-bottom:16px;font-size:15px">Type all the words you saw, one per line (order doesn't matter):</p>
    <textarea id="wf-answer" rows="6" style="width:100%;max-width:400px;background:var(--bg-card2);border:2px solid var(--border);color:var(--text-main);border-radius:12px;padding:16px;font-family:var(--font-display);font-size:18px;letter-spacing:2px;text-transform:uppercase;outline:none;transition:border-color 0.2s;resize:none" placeholder="WORD&#10;WORD&#10;..."></textarea>
    <br>
    <button class="btn-game btn-game-primary mt-3" id="wf-submit">Check Answers</button>
</div>

<div id="wf-result" style="display:none;text-align:center">
    <div id="wf-result-words" style="display:flex;flex-wrap:wrap;gap:10px;justify-content:center;margin-bottom:20px"></div>
    <button class="btn-game btn-game-primary mt-2" id="wf-next">Next Round →</button>
</div>

<script>
(function(){
    const DIFF_CFG={Easy:{words:4,flashTime:1200},Medium:{words:6,flashTime:900},Hard:{words:8,flashTime:700}};
    const diff=typeof DIFFICULTY!=='undefined'?DIFFICULTY:'Medium';
    const {words:wordCount,flashTime}=DIFF_CFG[diff]||DIFF_CFG.Medium;

    const WORD_BANK=['APPLE','TIGER','OCEAN','PIANO','CLOUD','DREAM','FLAME','STORM','NIGHT','LIGHT',
        'BREAD','STONE','RIVER','CHAIR','GRAPE','SWORD','MAGIC','FROST','EAGLE','CLOCK',
        'BRAIN','LASER','GHOST','MAPLE','CHESS','TOWER','BLAZE','CORAL','LUNAR','SWIFT',
        'DANCE','FLOCK','AMBER','CRISP','ORBIT','PEARL','QUAKE','RAVEN','SOLAR','TULIP'];

    let round=1,score=0,lives=3,currentWords=[],phase='idle';

    function setStatus(t,c='var(--text-muted)'){const el=document.getElementById('wf-status');el.textContent=t;el.style.color=c;}
    function updateLives(){document.getElementById('wf-lives').textContent='❤️'.repeat(Math.max(0,lives));}

    function start(){
        document.getElementById('wf-start').style.display='none';
        round=1;score=0;lives=3;
        document.getElementById('wf-round').textContent=1;
        document.getElementById('wf-score').textContent=0;
        updateLives();
        newRound();
    }

    async function newRound(){
        phase='showing';
        document.getElementById('wf-input-area').style.display='none';
        document.getElementById('wf-result').style.display='none';
        document.getElementById('wf-answer').value='';

        const shuffled=[...WORD_BANK].sort(()=>Math.random()-.5);
        currentWords=shuffled.slice(0,wordCount);

        const flashArea=document.getElementById('wf-flash-area');
        const wordEl=document.getElementById('wf-word');
        const counterEl=document.getElementById('wf-counter');
        flashArea.style.display='flex';
        setStatus('👀 Memorize these words!','var(--accent-cyan)');

        for(let i=0;i<currentWords.length;i++){
            wordEl.style.opacity='0';
            await delay(150);
            wordEl.textContent=currentWords[i];
            wordEl.style.opacity='1';
            counterEl.textContent=`Word ${i+1} of ${currentWords.length}`;
            await delay(flashTime);
        }
        wordEl.style.opacity='0';
        await delay(200);
        flashArea.style.display='none';

        phase='input';
        document.getElementById('wf-input-area').style.display='block';
        document.getElementById('wf-answer').focus();
        setStatus('🎯 Type all the words you remember!','var(--accent-orange)');
    }

    document.getElementById('wf-submit').addEventListener('click',checkAnswers);

    function checkAnswers(){
        if(phase!=='input')return;
        phase='result';
        const typed=document.getElementById('wf-answer').value
            .toUpperCase().trim().split(/\n+/).map(w=>w.trim()).filter(Boolean);

        const resultDiv=document.getElementById('wf-result-words');
        resultDiv.innerHTML='';
        let correct=0;

        currentWords.forEach(word=>{
            const found=typed.includes(word);
            if(found)correct++;
            const tag=document.createElement('div');
            tag.style.cssText=`padding:10px 20px;border-radius:10px;font-family:var(--font-display);font-size:16px;font-weight:700;letter-spacing:2px;border:2px solid ${found?'var(--accent-green)':'var(--accent-pink)'};background:${found?'rgba(56,176,0,0.12)':'rgba(255,0,110,0.1)'};color:${found?'var(--accent-green)':'var(--accent-pink)'}`;
            tag.textContent=(found?'✓ ':'✗ ')+word;
            resultDiv.appendChild(tag);
        });

        const roundScore=Math.round((correct/currentWords.length)*100*round);
        score+=roundScore;
        document.getElementById('wf-score').textContent=score;

        if(correct===currentWords.length){
            setStatus(`✅ Perfect! All ${correct} words correct! +${roundScore} pts`,'var(--accent-green)');
        } else if(correct>=Math.ceil(currentWords.length*0.5)){
            setStatus(`👍 ${correct}/${currentWords.length} words — not bad! +${roundScore} pts`,'var(--accent-orange)');
            lives--;updateLives();
        } else {
            setStatus(`❌ Only ${correct}/${currentWords.length} — try harder!`,'var(--accent-pink)');
            lives--;updateLives();
        }

        document.getElementById('wf-input-area').style.display='none';
        document.getElementById('wf-result').style.display='block';

        if(lives<=0){
            document.getElementById('wf-next').textContent='Game Over — Play Again';
            document.getElementById('wf-next').onclick=()=>{
                if(typeof submitScore==='function')submitScore(score);
                start();
            };
        } else {
            round++;
            document.getElementById('wf-round').textContent=round;
            document.getElementById('wf-next').textContent='Next Round →';
            document.getElementById('wf-next').onclick=newRound;
        }
    }

    function delay(ms){return new Promise(r=>setTimeout(r,ms));}
    document.getElementById('wf-start').addEventListener('click',start);
})();
</script>
