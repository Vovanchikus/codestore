import { Header } from "@/components/layouts/Header/Header";
import styles from "./page.module.css";
import { Sidebar } from "@/components/layouts/Sidebar/Sidebar";

export default function Home() {
  return (
    <div className="container">
      <Header />
      <Sidebar />
    </div>
  );
}
