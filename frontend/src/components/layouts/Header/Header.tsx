"use client";
import styles from "./header.module.scss";
import { HeaderMenu } from "./HeaderMenu/HeaderMenu";

function Header() {
  return (
    <header className={styles.header}>
      <HeaderMenu />
    </header>
  );
}

export { Header };
