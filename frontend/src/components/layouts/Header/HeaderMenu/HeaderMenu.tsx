"use client";
import Link from "next/link";
import styles from "./menu.module.scss";
import { HeaderItem } from "../HeaderItem/HeaderItem";
import { navigationItems } from "@/config/headerMenu.config";

function HeaderMenu() {
  return (
    <nav className={styles.menu}>
      <ul className={styles.menu__list}>
        {navigationItems.map((item) => (
          <HeaderItem key={item.href} label={item.label} href={item.href} />
        ))}
      </ul>
    </nav>
  );
}

export { HeaderMenu };
