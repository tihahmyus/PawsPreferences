const toggle = document.getElementById('menu-toggle');
  const nav = document.getElementById('nav-links');

  toggle.addEventListener('click', () => {
    nav.classList.toggle('show');
  });
  


// Config
const TOTAL_CATS = 12;        // bilangan kad
const IMG_W = 800, IMG_H = 1000; // saiz lebih besar, responsive by CSS

// Elements
const stack = document.getElementById('cardStack');
const summaryEl = document.getElementById('summary');
const likeCountEl = document.getElementById('likeCount');
const likedGrid = document.getElementById('likedGrid');
const likeBtn = document.getElementById('likeBtn');
const dislikeBtn = document.getElementById('dislikeBtn');

// State
let cards = [];
let liked = [];
let indexTop = 0; // pointer to top card (0..TOTAL_CATS-1)

// Build cat urls (cache-buster to reduce duplicates)
function buildCatUrl(i){
  const v = `${Date.now()}-${i}-${Math.random().toString(36).slice(2)}`;
  return `https://cataas.com/cat?width=${IMG_W}&height=${IMG_H}&v=${v}`;
}

// Render stack
function renderStack(){
  const urls = Array.from({length: TOTAL_CATS}, (_,i)=>buildCatUrl(i));
  urls.forEach((url, i)=>{
    const card = document.createElement('div');
    card.className = 'card';
    card.style.zIndex = 1000 - i;

    const img = document.createElement('img');
    img.src = url;
    img.alt = 'Cat';

    card.appendChild(img);
    stack.appendChild(card);
    cards.push({el: card, url, x:0, y:0, r:0});
  });

  indexTop = 0;
  attachInteraction();
}

function attachInteraction(){
  // Keyboard
  document.addEventListener('keydown', e=>{
    if (summaryEl && !summaryEl.classList.contains('hidden')) return;
    if (e.key === 'ArrowRight') choose('like');
    if (e.key === 'ArrowLeft')  choose('nope');
  });

  // Buttons
  likeBtn?.addEventListener('click', ()=>choose('like'));
  dislikeBtn?.addEventListener('click', ()=>choose('nope'));

  // Touch/drag for the top card only
  enableDragOnTop();
}

function topCard(){
  // last child is visually on top because appended last?
  // Here we manage pointer by indexTop
  return cards[indexTop]?.el || null;
}

function choose(type){
  const card = topCard();
  if(!card) return;
  if(type==='like'){ card.classList.add('badge-like'); liked.push(getTopUrl()); }
  else { card.classList.add('badge-nope'); }

  // animate out
  const dir = (type==='like') ? 1 : -1;
  card.style.transform = `translateX(${dir*420}px) rotate(${dir*18}deg)`;
  card.style.opacity = '0';

  // move pointer
  indexTop++;
  setTimeout(()=>{ card.remove(); afterPick(); }, 260);
}

function getTopUrl(){
  return cards[indexTop]?.url;
}

function afterPick(){
  if(indexTop >= cards.length){
    showSummary();
  }else{
    enableDragOnTop();
  }
}

function enableDragOnTop(){
  // remove old listeners by cloning
  const card = topCard();
  if(!card) return;
  const clone = card.cloneNode(true);
  card.replaceWith(clone);
  cards[indexTop].el = clone;

  let startX=0, currentX=0, dragging=false;

  const onStart = (x)=>{
    dragging=true; startX=x; currentX=0;
    clone.style.transition='none';
    clone.style.boxShadow='0 18px 38px rgba(0,0,0,.18)';
  };
  const onMove = (x)=>{
    if(!dragging) return;
    currentX = x - startX;
    const rotate = currentX/25;
    clone.style.transform = `translateX(${currentX}px) rotate(${rotate}deg)`;
    clone.classList.toggle('badge-like', currentX>80);
    clone.classList.toggle('badge-nope', currentX<-80);
  };
  const onEnd = ()=>{
    if(!dragging) return;
    dragging=false;
    clone.style.transition='transform .28s ease, opacity .28s ease, box-shadow .2s ease';
    clone.style.boxShadow='var(--shadow)';

    if(currentX > 110){ // like
      liked.push(getTopUrl());
      clone.style.transform = `translateX(420px) rotate(18deg)`;
      clone.style.opacity='0';
      indexTop++;
      setTimeout(()=>{ clone.remove(); afterPick(); }, 260);
    }else if(currentX < -110){ // nope
      clone.style.transform = `translateX(-420px) rotate(-18deg)`;
      clone.style.opacity='0';
      indexTop++;
      setTimeout(()=>{ clone.remove(); afterPick(); }, 260);
    }else{
      clone.style.transform = 'translateX(0) rotate(0)';
      clone.classList.remove('badge-like','badge-nope');
    }
  };

  // Mouse
  clone.addEventListener('mousedown', (e)=>onStart(e.clientX));
  window.addEventListener('mousemove', (e)=>onMove(e.clientX));
  window.addEventListener('mouseup', onEnd);

  // Touch
  clone.addEventListener('touchstart', (e)=>onStart(e.touches[0].clientX), {passive:true});
  clone.addEventListener('touchmove', (e)=>onMove(e.touches[0].clientX), {passive:true});
  clone.addEventListener('touchend', onEnd);
}

function showSummary(){
  // save to localStorage
  try{
    localStorage.setItem('likedCats', JSON.stringify(liked));
  }catch(e){/* ignore quota */}

  // build UI
  document.querySelector('.stack-wrap')?.classList.add('hidden');
  summaryEl.classList.remove('hidden');
  likeCountEl.textContent = liked.length.toString();

  likedGrid.innerHTML = '';
  (liked.length? liked : []).forEach(url=>{
    const img = document.createElement('img');
    img.src = url;
    img.alt = 'Liked cat';
    likedGrid.appendChild(img);
  });

  if(liked.length===0){
    const p = document.createElement('p');
    p.textContent = 'No likes yet. Try again!';
    likedGrid.appendChild(p);
  }
}

// Init
renderStack();
