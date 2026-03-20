<?php
// Difficulty settings
$cfg = [
    'Easy'   => ['pairs'=>6,  'time'=>999],
    'Medium' => ['pairs'=>8,  'time'=>999],
    'Hard'   => ['pairs'=>10, 'time'=>999],
];
?>
<div class="text-center mb-4">
    <div class="score-bar justify-content-center">
        <div class="score-box"><span class="score-num" id="cm-matches">0</span><span class="score-label">Matches</span></div>
        <div class="score-box"><span class="score-num" id="cm-moves">0</span><span class="score-label">Moves</span></div>
        <div class="score-box"><span class="score-num" id="cm-time">0</span><span class="score-label">Seconds</span></div>
    </div>
    <button class="btn-game btn-game-primary" id="cm-start">▶ Start Game</button>
</div>
<div id="memory-grid" style="display:none"></div>
<div id="cm-win" class="text-center" style="display:none">
    <div style="font-size:64px">🎉</div>
    <h3 class="section-title" style="color:var(--accent-green)">You Won!</h3>
    <p style="color:var(--text-muted)" class="mb-3">Completed in <strong id="cm-final-time"></strong>s with <strong id="cm-final-moves"></strong> moves.</p>
    <p style="color:var(--primary);font-family:var(--font-display);font-size:20px;font-weight:900">Score: <span id="cm-final-score"></span></p>
    <button class="btn-game btn-game-primary mt-3" id="cm-restart">Play Again</button>
</div>
<script>
(function(){
    const DIFF_CFG = {Easy:{pairs:6},Medium:{pairs:8},Hard:{pairs:10}};
    const diff = typeof DIFFICULTY!=='undefined'?DIFFICULTY:'Medium';
    const {pairs} = DIFF_CFG[diff]||DIFF_CFG.Medium;
    const allEmojis=['🦊','🐼','🦁','🐸','🦋','🐢','🦄','🐙','🦩','🐬'];
    const emojis = allEmojis.slice(0,pairs);
    const cols = pairs<=6?3:4;
    let flipped=[],matched=0,moves=0,timer,seconds=0,canFlip=true;

    document.getElementById('memory-grid').style.gridTemplateColumns=`repeat(${cols},1fr)`;
    document.getElementById('memory-grid').style.maxWidth=cols===3?'380px':'500px';

    function shuffle(arr){return[...arr].sort(()=>Math.random()-.5);}
    function startGame(){
        document.getElementById('cm-start').style.display='none';
        document.getElementById('cm-win').style.display='none';
        document.getElementById('memory-grid').style.display='grid';
        flipped=[];matched=0;moves=0;seconds=0;canFlip=true;
        document.getElementById('cm-matches').textContent=0;
        document.getElementById('cm-moves').textContent=0;
        document.getElementById('cm-time').textContent=0;
        clearInterval(timer);
        timer=setInterval(()=>{seconds++;document.getElementById('cm-time').textContent=seconds;},1000);
        renderBoard();
    }
    function renderBoard(){
        const grid=document.getElementById('memory-grid');
        grid.innerHTML='';
        shuffle([...emojis,...emojis]).forEach((emoji,i)=>{
            const card=document.createElement('div');
            card.className='mem-card';
            card.dataset.emoji=emoji;
            card.innerHTML=`<div class="mem-card-inner"><div class="mem-front"></div><div class="mem-back">${emoji}</div></div>`;
            card.addEventListener('click',()=>flipCard(card));
            grid.appendChild(card);
        });
    }
    function flipCard(card){
        if(!canFlip||card.classList.contains('flipped')||card.classList.contains('matched'))return;
        card.classList.add('flipped');flipped.push(card);
        if(flipped.length===2){
            canFlip=false;moves++;document.getElementById('cm-moves').textContent=moves;
            if(flipped[0].dataset.emoji===flipped[1].dataset.emoji){
                flipped.forEach(c=>c.classList.add('matched'));
                matched++;document.getElementById('cm-matches').textContent=matched;
                flipped=[];canFlip=true;
                if(matched===emojis.length)winGame();
            } else {
                flipped[0].classList.add('wrong');flipped[1].classList.add('wrong');
                setTimeout(()=>{flipped.forEach(c=>c.classList.remove('flipped','wrong'));flipped=[];canFlip=true;},900);
            }
        }
    }
    function winGame(){
        clearInterval(timer);
        const score=Math.max(0,Math.round((pairs*500)-(moves*20)-(seconds*5)));
        setTimeout(()=>{
            document.getElementById('memory-grid').style.display='none';
            document.getElementById('cm-final-time').textContent=seconds;
            document.getElementById('cm-final-moves').textContent=moves;
            document.getElementById('cm-final-score').textContent=score;
            document.getElementById('cm-win').style.display='block';
            if(typeof submitScore==='function')submitScore(score);
        },600);
    }
    document.getElementById('cm-start').addEventListener('click',startGame);
    document.getElementById('cm-restart').addEventListener('click',startGame);
})();
</script>
