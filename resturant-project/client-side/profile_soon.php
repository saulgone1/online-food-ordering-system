<!-- coming_soon.php -->
<style>
.coming-soon-container {
  min-height: 60vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: linear-gradient(120deg, #e8f5e9 70%, #f1f8e9 100%);
  border-radius: 18px;
  box-shadow: 0 4px 24px rgba(76,175,80,0.09);
  margin: 48px auto;
  max-width: 520px;
  padding: 48px 24px 56px 24px;
}
.coming-soon-icon {
  font-size: 3.5em;
  margin-bottom: 18px;
  color: #4caf50;
  opacity: 0.87;
  animation: bounce 1.2s infinite alternate;
}
@keyframes bounce {
  to { transform: translateY(-10px);}
}
.coming-soon-title {
  font-size: 2em;
  font-weight: 800;
  color: #388e3c;
  margin-bottom: 10px;
  letter-spacing: 0.02em;
}
.coming-soon-desc {
  color: #555;
  font-size: 1.13em;
  margin-bottom: 22px;
  text-align: center;
}
.coming-soon-footer {
  color: #aaa;
  font-size: 0.98em;
  margin-top: 14px;
}
</style>
<div class="coming-soon-container">
  <div class="coming-soon-icon">🚧</div>
  <div class="coming-soon-title">Coming Soon</div>
  <div class="coming-soon-desc">
    This section is under construction.<br>
    We’re working hard to bring you new features soon!
  </div>
  <div class="coming-soon-footer">
    Thank you for your patience.
  </div>
</div>
