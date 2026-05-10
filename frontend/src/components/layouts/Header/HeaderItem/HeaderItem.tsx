"use client";
import Link from "next/link";
import styles from "./item.module.scss";

interface HeaderItemProps {
  label: string;
  href: string;
}

function HeaderItem({ label, href }: HeaderItemProps) {
  return (
    <li className={styles.menu__item}>
      <Link href={href} className={styles.menu__link}>
        {label}
      </Link>
    </li>
  );
}

export { HeaderItem };
