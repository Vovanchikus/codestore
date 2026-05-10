"use client";
import styles from "./sidebar.module.scss";

function Sidebar() {
  return (
    <aside className={styles.sidebar}>
      <nav className={styles.menu}>
        <ul className={styles.menu__list}>
          <li className={styles.menu__item}>Пользователи</li>
          <li className={styles.menu__item}>Товары</li>
          <li className={styles.menu__item}>Заказы</li>
        </ul>
      </nav>
    </aside>
  );
}

export { Sidebar };
